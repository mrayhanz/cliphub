<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessVideoClip;
use App\Models\Clip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIToolController extends Controller
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
            'duration'        => $c->duration,
            'score'           => $c->score,
            'status'          => $c->status,
            'file_url'        => $c->file_url,
            'file_size_human' => $c->file_size_human,
        ]);

        $pendingClips = $allClips->whereIn('status', ['queued', 'processing'])->values()->map(fn($c) => [
            'id'       => $c->id,
            'title'    => $c->title,
            'hook'     => $c->hook,
            'duration' => $c->duration,
            'score'    => $c->score,
            'status'   => $c->status,
        ]);

        return view('kreator.ai_tools.index', compact('doneClips', 'pendingClips'));
    }

    /**
     * Terima URL → AI buat konsep → Dispatch job pemotongan
     */
    public function process(Request $request)
    {
        $request->validate([
            'url'      => 'required|url',
            'ratio'    => 'nullable|string',
            'duration' => 'nullable|string',
            'captions' => 'nullable|boolean',
        ]);

        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            return response()->json(['error' => 'Groq API Key belum dikonfigurasi di .env'], 500);
        }

        // Ekstrak Video ID Youtube
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $request->url, $matches);
        $videoId = $matches[1] ?? null;

        $transcriptText = $this->getYouTubeTranscript($request->url);
        $hasTranscript = !empty($transcriptText);

        // Susun Prompt sesuai ketersediaan transcript
        $durationPref = $request->duration === 'auto' ? '30-90' : ($request->duration === '30s' ? '15-30' : '30-60');
        
        $systemPrompt = 'You are an AI viral clip generator. Respond with ONLY a valid JSON object, no explanations, no markdown, no code blocks.';
        
        if ($hasTranscript) {
            // Batasi transcript ~4000 karakter agar tidak melebihi token (untuk Llama 3.1 8B margin aman)
            $safeTranscript = substr($transcriptText, 0, 16000); 
            $userPrompt = "Video URL: {$request->url}\n\nHere is the video transcript with timestamps:\n{$safeTranscript}\n\nIdentify the 3 most viral, engaging, and interesting moments from this transcript (e.g., strong opinions, money talks, high energy). Generate 3 TikTok/Reels clip concepts. Each clip should be $durationPref seconds long. Note the start_time and end_time strictly based on the provided timestamps (convert HH:MM:SS to total seconds). Return ONLY this JSON: {\"clips\":[{\"title\":\"Catchy Title\",\"hook\":\"The first spoken sentence or core lesson\",\"start_time\":60,\"end_time\":95,\"duration\":\"35s\",\"score\":92}]}.";
        } else {
            // Fallback (Seperti sebelumnya jika tidak ada teks, AI akan berhalusinasi/menebak)
            $userPrompt = "Video URL: {$request->url}. Generate 3 viral TikTok/Reels clip concepts. Return ONLY this JSON: {\"clips\":[{\"title\":\"string\",\"hook\":\"string\",\"start_time\":60,\"end_time\":105,\"duration\":\"45s\",\"score\":92}]}. Use realistic start/end times between 0-1200 seconds, each clip $durationPref seconds.";
        }

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
                    'temperature'     => 0.4, // Kurangi kreatifitas agar patuh pada transcript
                    'max_tokens'      => 1024,
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
            foreach ($data['clips'] as $clipData) {
                $startTime = (int) ($clipData['start_time'] ?? 0);
                $endTime   = (int) ($clipData['end_time']   ?? ($startTime + 45));

                $clip = Clip::create([
                    'user_id'      => auth()->id(),
                    'title'        => $clipData['title'] ?? 'Viral Clip',
                    'hook'         => $clipData['hook'] ?? null,
                    'source_url'   => $request->url,
                    'video_id'     => $videoId,
                    'ratio'        => $request->ratio ?? '9:16',
                    'has_captions' => $request->captions ?? false,
                    'transcript'   => null, // Bisa diisi dengan transkrip parsial jika dibutuhkan nanti
                    'start_time'   => $startTime,
                    'end_time'     => $endTime,
                    'duration'     => $clipData['duration'] ?? (($endTime - $startTime) . 's'),
                    'score'        => (int) ($clipData['score'] ?? 80),
                    'status'       => 'queued',
                ]);

                ProcessVideoClip::dispatch($clip);

                $createdClips[] = $clip->only(['id', 'title', 'hook', 'duration', 'score', 'status', 'start_time', 'end_time', 'ratio', 'has_captions']);
            }
            
            // Simpan info transcript ke session agar process mengetahui (optional)
            // ...

            return response()->json([
                'success' => true,
                'message' => count($createdClips) . ' klip sedang diproses. ' . ($hasTranscript ? 'AI membaca percakapan aktual.' : 'AI (Fallback) menebak timestamp karena tidak ada subtitle.'),
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
    private function getYouTubeTranscript($url)
    {
        $ytdlpBin = env('YTDLP_BIN_PATH', 'C:\\Users\\USER\\AppData\\Local\\Microsoft\\WinGet\\Links\\yt-dlp.exe');
        if (!file_exists($ytdlpBin)) $ytdlpBin = 'yt-dlp';

        $tmpDir = storage_path('app/tmp_subs');
        if (!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);

        $fileName = 'sub_' . strtolower(uniqid());
        $outputPath = $tmpDir . '/' . $fileName . '.%(ext)s';

        // Hanya unduh vtt auto atau manual subtitle
        $cmd = sprintf(
            '%s --write-auto-subs --write-subs --sub-lang id,en --skip-download --sub-format vtt -o %s %s 2>&1',
            escapeshellarg($ytdlpBin),
            escapeshellarg($outputPath),
            escapeshellarg($url)
        );

        exec($cmd, $output, $code);

        $files = glob($tmpDir . '/' . $fileName . '*');
        if (empty($files)) return null;

        $vttPath = $files[0];
        $vttContent = file_get_contents($vttPath);
        
        $lines = explode("\n", $vttContent);
        $transcript = "";
        $currentText = [];
        $currentTime = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/(\d{2}):(\d{2}):(\d{2})\.\d{3}\s*-->/', $line, $m)) {
                $sec = $m[1] * 3600 + $m[2] * 60 + $m[3];
                if ($sec - $currentTime >= 10 || empty($currentText)) {
                    if (!empty($currentText)) {
                        $transcript .= "[" . gmdate("H:i:s", $currentTime) . "] " . implode(" ", array_unique($currentText)) . "\n";
                    }
                    $currentText = [];
                    $currentTime = $sec;
                }
            } elseif ($line !== '' && !preg_match('/^[\d\s:]+$/', $line) && !str_starts_with($line, 'WEBVTT') && !str_starts_with($line, 'Language:')) {
                $cleanLine = trim(strip_tags(preg_replace('/<[^>]+>/', '', $line)));
                // Hilangkan baris aneh seperti 'NOTE' dll
                if ($cleanLine && !in_array($cleanLine, $currentText) && strlen($cleanLine) > 2) {
                     $currentText[] = $cleanLine;
                }
            }
        }
        if (!empty($currentText)) {
            $transcript .= "[" . gmdate("H:i:s", $currentTime) . "] " . implode(" ", array_unique($currentText)) . "\n";
        }

        // Cleanup
        foreach ($files as $f) @unlink($f);
        
        return $transcript;
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
            'file_url' => $clip->file_url,
            'file_size_human' => $clip->file_size_human,
        ]);
    }
}
