<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessVideoClip;
use App\Models\Clip;
use App\Support\AIClipperWorker;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AIClipperController extends Controller
{
    /**
     * Tampilkan halaman AI Auto-Clipper beserta riwayat klip user
     */
    public function index()
    {
        $allClips = Clip::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $doneClips = $allClips->where('status', 'done')->values()->map(fn($c) => [
            'id'              => $c->id,
            'title'           => $c->title,
            'hook'            => $c->hook,
            'video_id'        => $c->video_id,
            'start_time'      => $c->start_time,
            'end_time'        => $c->end_time,
            'timestamp_range' => $this->formatTimestampRange($c->start_time, $c->end_time),
            'ratio'           => $c->ratio,
            'duration'        => $c->duration,
            'resolution'      => $c->resolution,
            'has_captions'    => $c->has_captions,
            'score'           => $c->score,
            'status'          => $c->status,
            'file_url'        => $c->file_url,
            'file_size_human' => $c->file_size_human,
        ]);

        $pendingClips = $allClips->whereIn('status', ['queued', 'processing'])->values()->map(fn($c) => [
            'id'       => $c->id,
            'title'    => $c->title,
            'hook'     => $c->hook,
            'video_id' => $c->video_id,
            'start_time' => $c->start_time,
            'end_time' => $c->end_time,
            'timestamp_range' => $this->formatTimestampRange($c->start_time, $c->end_time),
            'ratio'    => $c->ratio,
            'duration' => $c->duration,
            'resolution' => $c->resolution,
            'has_captions' => $c->has_captions,
            'score'    => $c->score,
            'status'   => $c->status,
        ]);

        if ($pendingClips->isNotEmpty()) {
            AIClipperWorker::ensureRunning(true);
        }

        $failedClips = $allClips->where('status', 'failed')->values()->map(fn($c) => [
            'id'       => $c->id,
            'title'    => $c->title,
            'hook'     => $c->hook,
            'video_id' => $c->video_id,
            'duration' => $c->duration,
            'resolution' => $c->resolution,
            'has_captions' => $c->has_captions,
            'status'   => $c->status,
        ]);

        return view('kreator.ai_clipper.index', compact('doneClips', 'pendingClips', 'failedClips'));
    }

    /**
     * Terima URL → AI buat konsep → Dispatch job pemotongan
     */
    public function process(Request $request)
    {
        $request->validate([
            'url'      => 'required|url',
            'ratio'    => 'required|in:9:16,16:9',
            'resolution' => 'required|in:480,720,1080',
            'duration' => 'required|in:auto,15s,30s,60s',
            'clip_count' => 'required|integer|in:1,2,3,4,5',
            'captions' => 'nullable|boolean',
        ]);

        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            return response()->json(['error' => 'Groq API Key belum dikonfigurasi di .env'], 500);
        }

        // Ekstrak Video ID Youtube
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $request->url, $matches);
        $videoId = $matches[1] ?? null;

        if (!$videoId) {
            return response()->json([
                'success' => false,
                'error' => 'Link harus berupa URL video YouTube yang valid.',
            ], 422);
        }

        $transcriptData = $this->getYouTubeTranscript($request->url) ?? $this->transcribeYouTubeAudio($request->url, $apiKey);
        $transcriptText = $transcriptData['text'] ?? null;

        if (!$transcriptText) {
            return response()->json([
                'success' => false,
                'error' => 'Audio video belum berhasil ditranskripsi. Pastikan link YouTube bisa diakses, yt-dlp/ffmpeg tersedia, dan konfigurasi GROQ_API_KEY aktif.',
            ], 422);
        }

        // Susun prompt hanya dari transcript asli supaya AI tidak menebak isi video.
        [$minDuration, $maxDuration, $targetDuration] = $this->durationBounds($request->duration);
        $durationPref = $targetDuration ? "exactly {$targetDuration}" : "{$minDuration}-{$maxDuration}";
        $desiredClipCount = (int) $request->clip_count;
        
        $systemPrompt = 'Anda adalah editor video short-form berbahasa Indonesia, bukan peringkas subtitle. Pahami konteks transcript dan pilih momen yang benar-benar penting atau viral. Untuk edukasi, ambil konsep inti atau penjelasan yang actionable. Untuk podcast, ambil opini kuat, debat, cerita personal, klaim mengejutkan, atau insight tajam. Untuk hiburan, ambil punchline, momen lucu, reaksi, twist, atau energi viral. Semua title, hook, reason, dan teks output wajib bahasa Indonesia natural. Gunakan hanya transcript yang diberikan. Jangan mengarang fakta, judul, hook, timestamp, speaker, atau momen. Jangan memilih intro/pembukaan biasa kecuali memang ada inti penting. Klip harus non-overlap dan tersebar di beberapa bagian video. Jawab HANYA JSON valid, tanpa penjelasan, tanpa markdown, tanpa code block.';
        $safeTranscript = $this->buildClipCandidateContext($transcriptData['segments'] ?? [], $transcriptText);
        $userPrompt = "URL Video: {$request->url}\n\nKandidat transcript dari video asli:\n{$safeTranscript}\n\nPilih tepat {$desiredClipCount} momen short-form terkuat dari kandidat ini jika transcript mencukupi. Jangan random, jangan sekadar merangkum, dan jangan memotong pembukaan menjadi beberapa klip. Deteksi konteks tiap momen: edukasi, podcast, hiburan, atau campuran. Aturan pemilihan: edukasi = konsep penting atau penjelasan berguna; podcast = opini menarik, insight, perdebatan, cerita personal, atau statement mengejutkan; hiburan = momen lucu, punchline, reaksi, twist, atau energi viral. Setiap klip harus {$durationPref} detik. start_time dan end_time wajib dalam detik dari timestamp transcript. Semua klip wajib non-overlap, start_time antar klip harus berjauhan minimal durasi klip, dan pilih momen tersebar dari awal/tengah/akhir jika tersedia. Prioritaskan potongan yang punya setup dan payoff utuh. Semua title, hook, dan reason wajib bahasa Indonesia natural, walaupun transcript berbahasa Inggris. Return ONLY this JSON: {\"clips\":[{\"title\":\"Judul Indonesia yang spesifik dari topik asli\",\"hook\":\"Hook bahasa Indonesia dari inti momen\",\"content_type\":\"edukasi|podcast|hiburan|campuran\",\"reason\":\"Alasan singkat kenapa momen ini penting atau viral\",\"start_time\":60,\"end_time\":75,\"duration\":\"15s\",\"score\":92}]}";

        try {
            $response = Http::withToken($apiKey)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(60) // AI mungkin butuh waktu lebih lama membaca teks
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'    => 'llama-3.1-8b-instant',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt]
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature'     => 0.25,
                    'max_tokens'      => 1800,
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'AI gagal merespons: ' . $response->body(),
                ], 500);
            }

            $content = $response->json()['choices'][0]['message']['content'] ?? '';

            // Ekstrak JSON jika terbungkus markdown/teks lain
            if (!str_starts_with(trim($content), '{')) {
                preg_match('/\{.*\}/s', $content, $m);
                $content = $m[0] ?? '{}';
            }

            $data = json_decode($content, true);

            if (empty($data['clips'])) {
                return response()->json(['success' => false, 'error' => 'AI tidak menghasilkan data klip.'], 500);
            }

            // Buat record Clip & dispatch job pemotongan untuk setiap konsep
            $createdClips = [];
            $acceptedRanges = [];
            $fallbackCandidates = $this->fallbackClipCandidates(
                $transcriptData['segments'] ?? [],
                $desiredClipCount * 3,
                $targetDuration ?? $minDuration,
                $transcriptData['last_time'] ?? 0
            );
            $clipCandidates = collect($data['clips'])
                ->concat($fallbackCandidates)
                ->sortByDesc(fn($clip) => (int) Arr::get($clip, 'score', 80))
                ->values()
                ->all();

            foreach ($clipCandidates as $clipData) {
                if (count($createdClips) >= $desiredClipCount) {
                    break;
                }

                $startTime = max(0, (int) Arr::get($clipData, 'start_time', 0));
                $endTime = max($startTime + 1, (int) Arr::get($clipData, 'end_time', $startTime + 45));

                if (($transcriptData['last_time'] ?? 0) > 0 && $startTime > $transcriptData['last_time']) {
                    continue;
                }

                if ($targetDuration) {
                    $endTime = $startTime + $targetDuration;
                }

                if (($transcriptData['last_time'] ?? 0) > 0) {
                    if ($endTime > $transcriptData['last_time']) {
                        $endTime = $transcriptData['last_time'];
                        if ($targetDuration) {
                            $startTime = max(0, $endTime - $targetDuration);
                        }
                    }
                }

                $actualDuration = $endTime - $startTime;
                if ($targetDuration && $actualDuration !== $targetDuration) {
                    continue;
                } elseif ($actualDuration > $maxDuration) {
                    $endTime = $startTime + $maxDuration;
                } elseif ($actualDuration < $minDuration) {
                    $endTime = $startTime + $minDuration;
                    if (($transcriptData['last_time'] ?? 0) > 0) {
                        $endTime = min($endTime, $transcriptData['last_time']);
                    }
                }

                if ($endTime <= $startTime) {
                    continue;
                }

                if ($this->overlapsAcceptedRanges($startTime, $endTime, $acceptedRanges, $targetDuration ?? $minDuration)) {
                    continue;
                }

                $hook = $clipData['hook'] ?? $clipData['reason'] ?? null;

                $clip = Clip::create([
                    'user_id'      => auth()->id(),
                    'title'        => $clipData['title'] ?? 'Klip Viral',
                    'hook'         => $hook,
                    'source_url'   => $request->url,
                    'video_id'     => $videoId,
                    'ratio'        => $request->ratio,
                    'resolution'   => $request->resolution,
                    'has_captions' => $request->boolean('captions'),
                    'transcript'   => mb_substr($transcriptText, 0, 55000),
                    'transcript_segments' => json_encode($transcriptData['segments'] ?? [], JSON_UNESCAPED_UNICODE),
                    'start_time'   => $startTime,
                    'end_time'     => $endTime,
                    'duration'     => ($endTime - $startTime) . 's',
                    'score'        => (int) ($clipData['score'] ?? 80),
                    'status'       => 'queued',
                ]);

                ProcessVideoClip::dispatch($clip)->onQueue('ai-clips');

                $acceptedRanges[] = [$startTime, $endTime];

                $createdClips[] = array_merge(
                    $clip->only(['id', 'title', 'hook', 'video_id', 'duration', 'score', 'status', 'start_time', 'end_time', 'ratio', 'resolution', 'has_captions']),
                    ['timestamp_range' => $this->formatTimestampRange($clip->start_time, $clip->end_time)]
                );
            }

            if (empty($createdClips)) {
                return response()->json(['success' => false, 'error' => 'AI menghasilkan timestamp di luar transcript video. Silakan coba lagi dengan video yang subtitle-nya lebih lengkap.'], 500);
            }

            AIClipperWorker::ensureRunning(true);

            return response()->json([
                'success' => true,
                'message' => count($createdClips) . ' klip sedang diproses dari ' . (($transcriptData['source'] ?? '') === 'ai_transcription' ? 'transkripsi AI audio video.' : 'subtitle video asli.'),
                'clips'   => $createdClips,
                'videoId' => $videoId,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper Ekstrak Subtitle Menggunakan yt-dlp
     */
    private function getYouTubeTranscript($url): ?array
    {
        $ytdlpCmd = $this->ytDlpCommand();

        $tmpDir = storage_path('app/tmp_subs');
        if (!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);

        $fileName = 'sub_' . strtolower(uniqid());
        $outputPath = $tmpDir . '/' . $fileName . '.%(ext)s';

        // Hanya unduh vtt auto atau manual subtitle
        $cmd = sprintf(
            '%s --write-auto-subs --write-subs --sub-lang id,en --skip-download --sub-format vtt -o %s %s 2>&1',
            $ytdlpCmd,
            escapeshellarg($outputPath),
            escapeshellarg($url)
        );

        exec($cmd, $output, $code);

        $files = glob($tmpDir . '/' . $fileName . '*');
        if (empty($files)) return null;

        $vttPath = $files[0];
        $vttContent = file_get_contents($vttPath);

        // Cleanup
        foreach ($files as $f) @unlink($f);

        $data = $this->parseVttTranscript($vttContent);

        return $data ? array_merge($data, ['source' => 'youtube_subtitle']) : null;
    }

    private function transcribeYouTubeAudio(string $url, string $apiKey): ?array
    {
        $ytdlpCmd = $this->ytDlpCommand();
        $ffmpegBin = $this->ffmpegPath();

        $tmpDir = storage_path('app/tmp_subs');
        if (!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);

        $fileName = 'audio_' . strtolower(uniqid());
        $outputPath = $tmpDir . '/' . $fileName . '.%(ext)s';
        $cookiesFile = env('YT_COOKIES_PATH', storage_path('app/yt-cookies.txt'));
        $cookieArg = file_exists($cookiesFile) ? '--cookies ' . escapeshellarg($cookiesFile) : '';

        $cmd = sprintf(
            '%s --ffmpeg-location %s %s --no-playlist --no-warnings -f "ba/b" -x --audio-format mp3 --audio-quality 64K -o %s %s 2>&1',
            $ytdlpCmd,
            escapeshellarg($ffmpegBin),
            $cookieArg,
            escapeshellarg($outputPath),
            escapeshellarg($url)
        );

        exec($cmd, $output, $code);

        $files = glob($tmpDir . '/' . $fileName . '*');
        $audioPath = $files[0] ?? null;

        if (!$audioPath || !is_file($audioPath)) {
            Log::warning('AI Clipper transcription: audio download failed', [
                'exit_code' => $code,
                'output_tail' => array_slice($output, -8),
            ]);
            return null;
        }

        $handle = null;

        try {
            $handle = fopen($audioPath, 'r');
            $response = Http::withToken($apiKey)
                ->timeout(300)
                ->attach('file', $handle, basename($audioPath))
                ->post('https://api.groq.com/openai/v1/audio/transcriptions', [
                    'model' => env('GROQ_TRANSCRIPTION_MODEL', 'whisper-large-v3-turbo'),
                    'response_format' => 'verbose_json',
                    'temperature' => 0,
                    'timestamp_granularities[]' => 'segment',
                ]);

            if (!$response->successful()) {
                Log::warning('AI Clipper transcription: Groq transcription failed', [
                    'status' => $response->status(),
                    'body' => mb_substr($response->body(), 0, 1000),
                ]);
                return null;
            }

            $segments = collect($response->json('segments', []))
                ->map(function ($segment) {
                    return [
                        'start' => (float) Arr::get($segment, 'start', 0),
                        'end' => (float) Arr::get($segment, 'end', 0),
                        'text' => trim((string) Arr::get($segment, 'text', '')),
                    ];
                })
                ->filter(fn($segment) => $segment['text'] !== '' && $segment['end'] > $segment['start'])
                ->values()
                ->all();

            if (empty($segments)) {
                Log::warning('AI Clipper transcription: Groq returned no timestamp segments', [
                    'body' => mb_substr(json_encode($response->json(), JSON_UNESCAPED_UNICODE), 0, 1000),
                ]);
                return null;
            }

            return array_merge($this->segmentsToTranscriptData($segments), ['source' => 'ai_transcription']);
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }

            foreach ($files as $file) {
                @unlink($file);
            }
        }
    }

    private function parseVttTranscript(string $vttContent): ?array
    {
        preg_match_all(
            '/(?<start>\d{1,2}:\d{2}(?::\d{2})?[\.,]\d{3})\s*-->\s*(?<end>\d{1,2}:\d{2}(?::\d{2})?[\.,]\d{3})(?<settings>[^\r\n]*)\R(?<text>.*?)(?=\R{2,}|\z)/s',
            $vttContent,
            $matches,
            PREG_SET_ORDER
        );

        $segments = [];

        foreach ($matches as $match) {
            $text = html_entity_decode(trim(strip_tags($match['text'])), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $text = preg_replace('/\s+/', ' ', $text);

            if ($text === '' || str_starts_with($text, 'NOTE')) {
                continue;
            }

            $segments[] = [
                'start' => $this->timestampToSecondsFloat($match['start']),
                'end' => $this->timestampToSecondsFloat($match['end']),
                'text' => $text,
            ];
        }

        if (empty($segments)) {
            return null;
        }

        return $this->segmentsToTranscriptData($segments);
    }

    private function segmentsToTranscriptData(array $segments): array
    {
        $transcript = '';
        $bucketStart = null;
        $bucketText = [];
        $lastTime = 0;

        foreach ($segments as $segment) {
            $start = (float) $segment['start'];
            $end = (float) $segment['end'];
            $text = trim((string) $segment['text']);

            if ($text === '') {
                continue;
            }

            if ($bucketStart === null) {
                $bucketStart = (int) floor($start);
            }

            if ($start - $bucketStart >= 10 && !empty($bucketText)) {
                $transcript .= '[' . gmdate('H:i:s', $bucketStart) . '] ' . implode(' ', array_unique($bucketText)) . "\n";
                $bucketStart = (int) floor($start);
                $bucketText = [];
            }

            $bucketText[] = $text;
            $lastTime = max($lastTime, (int) ceil($end));
        }

        if ($bucketStart !== null && !empty($bucketText)) {
            $transcript .= '[' . gmdate('H:i:s', $bucketStart) . '] ' . implode(' ', array_unique($bucketText)) . "\n";
        }

        return [
            'text' => trim($transcript),
            'segments' => $segments,
            'last_time' => $lastTime,
        ];
    }

    private function buildClipCandidateContext(array $segments, string $fallbackTranscript): string
    {
        if (empty($segments)) {
            return mb_substr($fallbackTranscript, 0, 8000);
        }

        $windows = [];
        $windowStart = null;
        $windowEnd = null;
        $windowText = [];

        foreach ($segments as $segment) {
            $start = (float) Arr::get($segment, 'start', 0);
            $end = (float) Arr::get($segment, 'end', $start);
            $text = trim((string) Arr::get($segment, 'text', ''));

            if ($text === '') {
                continue;
            }

            if ($windowStart === null) {
                $windowStart = $start;
                $windowEnd = $end;
            }

            if (($end - $windowStart) > 75 && !empty($windowText)) {
                $windows[] = $this->buildCandidateWindow($windowStart, $windowEnd, $windowText);
                $windowStart = $start;
                $windowText = [];
            }

            $windowEnd = $end;
            $windowText[] = $text;
        }

        if ($windowStart !== null && !empty($windowText)) {
            $windows[] = $this->buildCandidateWindow($windowStart, $windowEnd, $windowText);
        }

        usort($windows, fn($a, $b) => $b['score'] <=> $a['score']);

        $selected = array_slice($windows, 0, 20);
        usort($selected, fn($a, $b) => $a['start'] <=> $b['start']);

        $context = '';
        foreach ($selected as $window) {
            $line = '[' . gmdate('H:i:s', (int) floor($window['start'])) . '-' . gmdate('H:i:s', (int) ceil($window['end'])) . '] ' . $window['text'] . "\n";

            if (mb_strlen($context . $line) > 10000) {
                break;
            }

            $context .= $line;
        }

        return trim($context) !== '' ? trim($context) : mb_substr($fallbackTranscript, 0, 8000);
    }

    private function buildCandidateWindow(float $start, float $end, array $texts): array
    {
        $text = trim(preg_replace('/\s+/', ' ', implode(' ', array_unique($texts))));
        $lower = mb_strtolower($text);
        $keywords = [
            'rahasia', 'tips', 'cara', 'kenapa', 'jangan', 'penting', 'ternyata',
            'viral', 'uang', 'profit', 'rugi', 'mahal', 'murah', 'salah', 'benar',
            'masalah', 'solusi', 'hasil', 'bukti', 'cerita', 'shock', 'kaget',
            'secret', 'tips', 'money', 'mistake', 'problem', 'solution', 'result',
            'konsep', 'strategi', 'contoh', 'intinya', 'kesimpulan', 'pelajaran',
            'menurut saya', 'gue pikir', 'opini', 'setuju', 'nggak setuju',
            'lucu', 'ngakak', 'aneh', 'gila', 'kok bisa', 'plot twist',
            'concept', 'strategy', 'example', 'lesson', 'opinion', 'agree',
            'disagree', 'funny', 'crazy', 'twist', 'punchline', 'insight',
        ];

        $score = min(mb_strlen($text), 800) / 20;
        foreach ($keywords as $keyword) {
            if (str_contains($lower, $keyword)) {
                $score += 18;
            }
        }

        $score += substr_count($text, '?') * 12;
        $score += substr_count($text, '!') * 8;

        if (($end - $start) >= 20 && ($end - $start) <= 90) {
            $score += 20;
        }

        return [
            'start' => $start,
            'end' => $end,
            'text' => mb_substr($text, 0, 900),
            'score' => $score,
        ];
    }

    private function durationBounds(string $duration): array
    {
        return match ($duration) {
            '15s' => [15, 15, 15],
            '30s' => [30, 30, 30],
            '60s' => [60, 60, 60],
            default => [15, 60, null],
        };
    }

    private function desiredClipCount(int $videoDuration, int $targetDuration): int
    {
        if ($videoDuration <= 0) {
            return 6;
        }

        $minutes = max(1, (int) ceil($videoDuration / 60));
        $byDuration = (int) floor($videoDuration / max(15, $targetDuration * 3));

        return max(3, min(24, max($byDuration, (int) ceil($minutes / 2))));
    }

    private function overlapsAcceptedRanges(int $startTime, int $endTime, array $acceptedRanges, int $targetDuration): bool
    {
        $minimumStartGap = max(10, (int) floor($targetDuration * 0.8));

        foreach ($acceptedRanges as [$acceptedStart, $acceptedEnd]) {
            $overlapStart = max($startTime, $acceptedStart);
            $overlapEnd = min($endTime, $acceptedEnd);

            if ($overlapEnd > $overlapStart) {
                return true;
            }

            if (abs($startTime - $acceptedStart) < $minimumStartGap) {
                return true;
            }
        }

        return false;
    }

    private function fallbackClipCandidates(array $segments, int $limit, int $targetDuration, int $lastTime): array
    {
        if (empty($segments) || $targetDuration <= 0) {
            return [];
        }

        $windows = [];
        $step = max(10, (int) floor($targetDuration * 0.75));
        $maxStart = $lastTime > 0 ? max(0, $lastTime - $targetDuration) : null;

        for ($start = 0; $maxStart === null || $start <= $maxStart; $start += $step) {
            $end = $start + $targetDuration;
            $texts = [];

            foreach ($segments as $segment) {
                $segmentStart = (float) Arr::get($segment, 'start', 0);
                $segmentEnd = (float) Arr::get($segment, 'end', $segmentStart);

                if ($segmentEnd < $start || $segmentStart > $end) {
                    continue;
                }

                $text = trim((string) Arr::get($segment, 'text', ''));
                if ($text !== '') {
                    $texts[] = $text;
                }
            }

            if (count($texts) < 2) {
                if ($maxStart === null) {
                    break;
                }
                continue;
            }

            $window = $this->buildCandidateWindow($start, $end, $texts);
            $windows[] = [
                'title' => 'Momen Penting Video',
                'hook' => mb_substr($window['text'], 0, 120),
                'content_type' => 'campuran',
                'reason' => 'Bagian ini memiliki konteks yang cukup kuat dari transcript asli.',
                'start_time' => $start,
                'end_time' => $end,
                'duration' => $targetDuration . 's',
                'score' => max(60, min(88, (int) round($window['score']))),
            ];

            if ($maxStart === null && $start > 1800) {
                break;
            }
        }

        usort($windows, fn($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($windows, 0, $limit);
    }

    private function formatTimestampRange(int $startTime, int $endTime): string
    {
        return $this->formatSeconds($startTime) . '-' . $this->formatSeconds($endTime);
    }

    private function formatSeconds(int $seconds): string
    {
        $seconds = max(0, $seconds);
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
        }

        return sprintf('%02d:%02d', $minutes, $remainingSeconds);
    }

    private function timestampToSeconds(string $timestamp): int
    {
        $timestamp = str_replace(',', '.', $timestamp);
        $parts = explode(':', $timestamp);
        $seconds = 0.0;

        foreach ($parts as $part) {
            $seconds = ($seconds * 60) + (float) $part;
        }

        return (int) floor($seconds);
    }

    private function timestampToSecondsFloat(string $timestamp): float
    {
        $timestamp = str_replace(',', '.', $timestamp);
        $parts = explode(':', $timestamp);
        $seconds = 0.0;

        foreach ($parts as $part) {
            $seconds = ($seconds * 60) + (float) $part;
        }

        return $seconds;
    }

    private function ytDlpCommand(): string
    {
        $envPath = env('YTDLP_BIN_PATH');

        if ($envPath && file_exists($envPath)) {
            return escapeshellarg($envPath);
        }

        $wingetPath = getenv('LOCALAPPDATA') . '\\Microsoft\\WinGet\\Links\\yt-dlp.exe';
        if ($wingetPath && file_exists($wingetPath)) {
            return escapeshellarg($wingetPath);
        }

        $localModule = storage_path('app/python-tools/yt_dlp/__main__.py');
        if (file_exists($localModule)) {
            return escapeshellarg($this->pythonPath()) . ' ' . escapeshellarg($localModule);
        }

        return 'yt-dlp';
    }

    private function ffmpegPath(): string
    {
        $envPath = env('FFMPEG_BIN_PATH');

        if ($envPath && file_exists($envPath)) {
            return $envPath;
        }

        $wingetPath = getenv('LOCALAPPDATA') . '\\Microsoft\\WinGet\\Links\\ffmpeg.exe';
        if ($wingetPath && file_exists($wingetPath)) {
            return $wingetPath;
        }

        $localBinaries = glob(storage_path('app/python-tools/imageio_ffmpeg/binaries/ffmpeg*.exe'));
        if (!empty($localBinaries)) {
            return $localBinaries[0];
        }

        return 'ffmpeg';
    }

    private function pythonPath(): string
    {
        $pythonPath = trim((string) shell_exec('where python 2>NUL'));

        if ($pythonPath !== '') {
            return strtok($pythonPath, "\r\n");
        }

        return 'python';
    }

    /**
     * Cek status klip (polling dari Frontend)
     */
    public function status(Clip $clip)
    {
        abort_unless($clip->user_id === auth()->id(), 403);

        return response()->json([
            'id'       => $clip->id,
            'status'   => $clip->status,
            'timestamp_range' => $this->formatTimestampRange($clip->start_time, $clip->end_time),
            'ratio' => $clip->ratio,
            'resolution' => $clip->resolution,
            'has_captions' => $clip->has_captions,
            'file_url' => $clip->file_url,
            'file_size_human' => $clip->file_size_human,
        ]);
    }

    public function destroy(Clip $clip)
    {
        abort_unless($clip->user_id === auth()->id(), 403);
        abort_unless($clip->status === 'done', 422);

        $this->deleteClipFiles($clip);

        $clip->delete();

        return response()->json([
            'success' => true,
            'message' => 'Klip berhasil dihapus.',
        ]);
    }

    public function cancel(Clip $clip)
    {
        abort_unless($clip->user_id === auth()->id(), 403);
        abort_unless(in_array($clip->status, ['queued', 'processing']), 422);

        $clip->update(['status' => 'cancelled']);
        $this->deleteClipFiles($clip);

        return response()->json([
            'success' => true,
            'message' => 'Pembuatan klip dibatalkan. Video yang dibatalkan otomatis dihapus.',
        ]);
    }

    private function deleteClipFiles(Clip $clip): void
    {
        if ($clip->file_path) {
            Storage::disk('public')->delete($clip->file_path);
        }

        Storage::disk('public')->delete('clips/' . $clip->id . '.mp4');

        $tempDir = storage_path('app/tmp_subs');
        foreach (glob($tempDir . '/*_' . $clip->id . '_*') ?: [] as $tempFile) {
            if (is_file($tempFile)) {
                @unlink($tempFile);
            }
        }
    }
}
