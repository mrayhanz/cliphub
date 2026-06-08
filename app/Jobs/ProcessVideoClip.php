<?php

namespace App\Jobs;

use App\Models\Clip;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessVideoClip implements ShouldQueue
{
    use Queueable;

    public $timeout = 600; // 10 menit
    public $tries   = 1;

    public function __construct(public Clip $clip) {}

    public function handle(): void
    {
        $clip = $this->clip->fresh();
        if (!$clip) {
            return;
        }

        if ($clip->status === 'cancelled') {
            $this->cleanupClipFiles($clip);
            return;
        }

        $clip->update(['status' => 'processing']);

        try {
            $outputDir  = storage_path('app/public/clips');
            if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);
            $outputFile = $outputDir . '/' . $clip->id . '.mp4';

            $ytdlpBin  = $this->ytDlpCommand();
            $ffmpegBin = $this->ffmpegPath();

            // 1. Get Direct Stream URL via yt-dlp
            Log::info("ClipHub: Getting stream URL for clip #{$clip->id}");

            // Support optional cookies file for YouTube bot detection bypass
            $cookiesFile = env('YT_COOKIES_PATH', storage_path('app/yt-cookies.txt'));
            $cookieArg   = (file_exists($cookiesFile)) ? '--cookies ' . escapeshellarg($cookiesFile) : '';

            $duration = max(1, $clip->end_time - $clip->start_time);
            $hasCaptions = $clip->has_captions === true;

            Log::info("Clipfluence: Downloading section with yt-dlp natively. Start: {$clip->start_time}s, Duration: {$duration}s, Ratio: {$clip->ratio}, Resolution: {$clip->resolution}, Captions: " . ($hasCaptions ? 'Yes' : 'No'));

            $tempDir = storage_path('app/tmp_subs');
            if (!is_dir($tempDir)) @mkdir($tempDir, 0755, true);

            $tempInputVideo = $tempDir . '/tempvid_' . $clip->id . '_' . uniqid() . '.mp4';

            $ytdlpCmd = sprintf(
                '%s --ffmpeg-location %s %s --no-warnings -f "bv[ext=mp4]+ba[ext=m4a]/b[ext=mp4]/b" --force-keyframes-at-cuts --download-sections %s -o %s %s 2>&1',
                $ytdlpBin,
                escapeshellarg($ffmpegBin),
                $cookieArg,
                escapeshellarg('*' . $clip->start_time . '-' . $clip->end_time),
                escapeshellarg($tempInputVideo),
                escapeshellarg($clip->source_url)
            );

            exec($ytdlpCmd, $ytOutput, $ytCode);

            if ($this->wasCancelled($clip, [$tempInputVideo, $outputFile])) {
                return;
            }
            
            // Periksa apakah hasil potongan pertama berhasil diunduh ke disk
            if (!file_exists($tempInputVideo)) {
                throw new \Exception("yt-dlp gagal mendownload/memotong stream video.");
            }

            // 2. Proses FFmpeg secara lokal (Crop & Styling)
            $videoFilters = [];
            $vttPathToCleanup = null;

            [$targetWidth, $targetHeight] = $this->targetDimensions($clip->ratio, $clip->resolution);

            // Handle Aspect Ratio & Output Resolution
            if ($clip->ratio === '9:16') {
                $videoFilters[] = $this->smartVerticalCropFilter($tempInputVideo, $targetWidth, $targetHeight)
                    ?? "scale={$targetWidth}:{$targetHeight}:force_original_aspect_ratio=increase,crop={$targetWidth}:{$targetHeight}";
            } else {
                $videoFilters[] = "scale={$targetWidth}:{$targetHeight}:force_original_aspect_ratio=decrease,pad={$targetWidth}:{$targetHeight}:(ow-iw)/2:(oh-ih)/2";
            }

            // Handle AI Auto-Captions (Hardsubbing)
            if ($hasCaptions) {
                $vttPathToCleanup = $this->createClipSubtitleFromStoredSegments($clip, $tempDir);

                if (!$vttPathToCleanup) {
                    $vttName = 'vid_' . $clip->id . '_' . uniqid();
                    $vttBasePattern = escapeshellarg($tempDir . '/' . $vttName . '.%(ext)s');

                    $ytdlpSubCmd = sprintf(
                        '%s --write-auto-subs --write-subs --sub-lang id,en --skip-download --sub-format vtt -o %s %s 2>&1',
                        $ytdlpBin,
                        $vttBasePattern,
                        escapeshellarg($clip->source_url)
                    );

                    exec($ytdlpSubCmd);

                    if ($this->wasCancelled($clip, [$tempInputVideo, $outputFile])) {
                        return;
                    }

                    $files = glob($tempDir . '/' . $vttName . '*');
                    if (!empty($files)) {
                        $vttPathToCleanup = $this->createClipSubtitle($files[0], $clip->start_time, $clip->end_time, $tempDir, $clip->id) ?? $files[0];
                    }
                }

                if ($vttPathToCleanup) {
                    // FFmpeg subtitles syntax requires escaped absolute paths on Windows
                    $escapedPath = str_replace('\\', '/', $vttPathToCleanup);
                    $escapedPath = str_replace(':', '\\\\:', $escapedPath);
                    
                    $subStyle = "force_style='FontSize=24,PrimaryColour=&H00FFFFFF&,OutlineColour=&H00000000&,BackColour=&H99000000&,BorderStyle=1,Outline=3,Shadow=1,MarginV=44,Bold=-1,Alignment=2'";
                    $videoFilters[] = "subtitles={$escapedPath}:{$subStyle}";
                } else {
                    Log::warning("Clipfluence: Gagal membuat subtitle untuk Video {$clip->source_url}");
                }
            }

            $vfArgument = '';
            if (!empty($videoFilters)) {
                $vfArgument = '-vf ' . escapeshellarg(implode(',', $videoFilters));
            }

            // FFmpeg merender dari input disk lokal sehingga bebas dari blokir 403 HTTP Google!
            $ffmpegCmd = sprintf(
                '%s -y -i %s -t %d -map 0:v:0 -map 0:a? -sn %s -c:v libx264 -c:a aac -preset fast -movflags +faststart %s 2>&1',
                escapeshellarg($ffmpegBin),
                escapeshellarg($tempInputVideo),
                $duration,
                $vfArgument,
                escapeshellarg($outputFile)
            );

            if ($this->wasCancelled($clip, [$tempInputVideo, $outputFile])) {
                return;
            }

            exec($ffmpegCmd, $ffOutput, $ffCode);

            // Cleanup Local Temp MP4
            if (file_exists($tempInputVideo)) {
                @unlink($tempInputVideo);
            }

            // Cleanup VTT
            if ($vttPathToCleanup && file_exists($vttPathToCleanup)) {
                @unlink($vttPathToCleanup);
            }
            foreach (glob($tempDir . '/vid_' . $clip->id . '_*') as $subtitleTempFile) {
                @unlink($subtitleTempFile);
            }

            if ($this->wasCancelled($clip, [$outputFile])) {
                return;
            }

            if ($ffCode !== 0 || !file_exists($outputFile)) {
                Log::error("FFmpeg Error: " . implode("\n", array_slice($ffOutput, -10)));
                throw new \Exception("FFmpeg gagal memotong stream.");
            }

            $fileSize = filesize($outputFile);
            $filePath = 'clips/' . $clip->id . '.mp4';

            $clip->update([
                'status'    => 'done',
                'file_path' => $filePath,
                'file_size' => $fileSize,
            ]);

        } catch (\Throwable $e) {
            Log::error("Clipfluence Core Error: " . $e->getMessage());
            $freshClip = $clip->fresh();
            if ($freshClip && $freshClip->status !== 'cancelled') {
                $freshClip->update(['status' => 'failed']);
            }
        }
    }

    private function wasCancelled(Clip $clip, array $pathsToDelete = []): bool
    {
        $freshClip = $clip->fresh();

        if (!$freshClip || $freshClip->status === 'cancelled') {
            foreach ($pathsToDelete as $path) {
                if ($path && is_file($path)) {
                    @unlink($path);
                }
            }

            if ($freshClip) {
                $this->cleanupClipFiles($freshClip);
            }

            return true;
        }

        return false;
    }

    private function cleanupClipFiles(Clip $clip): void
    {
        Storage::disk('public')->delete('clips/' . $clip->id . '.mp4');

        if ($clip->file_path) {
            Storage::disk('public')->delete($clip->file_path);
        }

        $tempDir = storage_path('app/tmp_subs');
        foreach (glob($tempDir . '/*_' . $clip->id . '_*') ?: [] as $tempFile) {
            if (is_file($tempFile)) {
                @unlink($tempFile);
            }
        }
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

    private function targetDimensions(string $ratio, ?string $resolution): array
    {
        $resolution = rtrim((string) $resolution, 'p');

        if ($ratio === '9:16') {
            return match ($resolution) {
                '480' => [480, 854],
                '720' => [720, 1280],
                default => [1080, 1920],
            };
        }

        return match ($resolution) {
            '480' => [854, 480],
            '720' => [1280, 720],
            default => [1920, 1080],
        };
    }

    private function smartVerticalCropFilter(string $videoPath, int $targetWidth, int $targetHeight): ?string
    {
        $scriptPath = app_path('Support/smart_crop.py');

        if (!file_exists($scriptPath)) {
            return null;
        }

        $cmd = sprintf(
            '%s %s %s %d %d 2>&1',
            escapeshellarg($this->pythonPath()),
            escapeshellarg($scriptPath),
            escapeshellarg($videoPath),
            $targetWidth,
            $targetHeight
        );

        exec($cmd, $output, $code);

        if ($code !== 0 || empty($output)) {
            Log::warning('ClipHub smart crop failed', [
                'exit_code' => $code,
                'output_tail' => array_slice($output, -5),
            ]);
            return null;
        }

        $data = json_decode(trim(end($output)), true);

        if (!is_array($data) || empty($data['ok']) || empty($data['filter'])) {
            Log::warning('ClipHub smart crop returned invalid output', [
                'output_tail' => array_slice($output, -5),
            ]);
            return null;
        }

        Log::info('ClipHub smart crop filter selected', [
            'tracked' => $data['tracked'] ?? false,
            'points' => $data['points'] ?? 0,
        ]);

        return $data['filter'];
    }

    private function createClipSubtitle(string $sourcePath, int $startTime, int $endTime, string $tempDir, int $clipId): ?string
    {
        $content = file_get_contents($sourcePath);
        if (!$content) {
            return null;
        }

        preg_match_all(
            '/(?<start>\d{1,2}:\d{2}(?::\d{2})?[\.,]\d{3})\s*-->\s*(?<end>\d{1,2}:\d{2}(?::\d{2})?[\.,]\d{3})(?<settings>[^\r\n]*)\R(?<text>.*?)(?=\R{2,}|\z)/s',
            $content,
            $matches,
            PREG_SET_ORDER
        );

        if (empty($matches)) {
            return null;
        }

        $cues = [];

        foreach ($matches as $match) {
            $cueStart = $this->timestampToSecondsFloat($match['start']);
            $cueEnd = $this->timestampToSecondsFloat($match['end']);

            if ($cueEnd < $startTime || $cueStart > $endTime) {
                continue;
            }

            $shiftedStart = max(0, $cueStart - $startTime);
            $shiftedEnd = min($endTime - $startTime, $cueEnd - $startTime);

            if ($shiftedEnd <= $shiftedStart) {
                continue;
            }

            $text = trim(strip_tags($match['text']));
            $text = html_entity_decode(preg_replace('/\s+/', ' ', $text), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            if ($text === '') {
                continue;
            }

            $cues[] = [
                'start' => $shiftedStart,
                'end' => $shiftedEnd,
                'text' => $text,
            ];
        }

        $vtt = $this->buildReadableSubtitleVtt($cues);

        if (!$vtt) {
            return null;
        }

        $targetPath = $tempDir . '/clip_sub_' . $clipId . '_' . uniqid() . '.vtt';
        file_put_contents($targetPath, $vtt);

        return $targetPath;
    }

    private function createClipSubtitleFromStoredSegments(Clip $clip, string $tempDir): ?string
    {
        $segments = json_decode((string) $clip->transcript_segments, true);

        if (!is_array($segments) || empty($segments)) {
            return null;
        }

        $cues = [];

        foreach ($segments as $segment) {
            $cueStart = (float) ($segment['start'] ?? 0);
            $cueEnd = (float) ($segment['end'] ?? 0);

            if ($cueEnd < $clip->start_time || $cueStart > $clip->end_time) {
                continue;
            }

            $shiftedStart = max(0, $cueStart - $clip->start_time);
            $shiftedEnd = min($clip->end_time - $clip->start_time, $cueEnd - $clip->start_time);

            if ($shiftedEnd <= $shiftedStart) {
                continue;
            }

            $text = trim((string) ($segment['text'] ?? ''));

            if ($text === '') {
                continue;
            }

            $cues[] = [
                'start' => $shiftedStart,
                'end' => $shiftedEnd,
                'text' => $text,
            ];
        }

        $vtt = $this->buildReadableSubtitleVtt($cues);

        if (!$vtt) {
            return null;
        }

        $targetPath = $tempDir . '/clip_ai_sub_' . $clip->id . '_' . uniqid() . '.vtt';
        file_put_contents($targetPath, $vtt);

        return $targetPath;
    }

    private function buildReadableSubtitleVtt(array $sourceCues): ?string
    {
        $gap = 0.18;
        $maxChars = 42;
        $previousFullText = '';
        $readableCues = [];

        foreach ($sourceCues as $sourceCue) {
            $start = (float) ($sourceCue['start'] ?? 0);
            $end = (float) ($sourceCue['end'] ?? 0);
            $text = $this->cleanSubtitleText((string) ($sourceCue['text'] ?? ''));

            if ($text === '' || $end <= $start) {
                continue;
            }

            $fullText = $text;

            if ($previousFullText !== '' && str_starts_with(mb_strtolower($text), mb_strtolower($previousFullText))) {
                $remainder = trim(mb_substr($text, mb_strlen($previousFullText)));
                if (mb_strlen($remainder) >= 3) {
                    $text = $remainder;
                }
            }

            $chunks = $this->splitSubtitleText($text, $maxChars);
            $chunkCount = count($chunks);

            if ($chunkCount === 0) {
                continue;
            }

            $duration = max(0.75, $end - $start);
            $availableDuration = max(0.75, $duration - ($gap * max(0, $chunkCount - 1)));
            $chunkDuration = max(0.75, $availableDuration / $chunkCount);
            $cursor = $start;

            foreach ($chunks as $chunk) {
                $cueEnd = min($end, $cursor + $chunkDuration);

                if ($cueEnd - $cursor < 0.45) {
                    break;
                }

                if (!empty($readableCues)) {
                    $lastIndex = count($readableCues) - 1;
                    $lastEnd = $readableCues[$lastIndex]['end'];

                    if ($cursor < $lastEnd + $gap) {
                        $cursor = $lastEnd + $gap;
                        $cueEnd = min($end, $cursor + $chunkDuration);
                    }
                }

                if ($cueEnd <= $cursor) {
                    break;
                }

                $readableCues[] = [
                    'start' => $cursor,
                    'end' => $cueEnd,
                    'text' => $chunk,
                ];

                $cursor = $cueEnd + $gap;
            }

            $previousFullText = $fullText;
        }

        if (empty($readableCues)) {
            return null;
        }

        $vtt = "WEBVTT\n\n";

        foreach ($readableCues as $cue) {
            $vtt .= $this->secondsToVttTimestamp($cue['start']) . ' --> ' . $this->secondsToVttTimestamp($cue['end']) . "\n";
            $vtt .= $cue['text'] . "\n\n";
        }

        return $vtt;
    }

    private function cleanSubtitleText(string $text): string
    {
        $text = preg_replace('/<\d{1,2}:\d{2}(?::\d{2})?[\.,]\d{3}>/', ' ', $text);
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    private function splitSubtitleText(string $text, int $maxChars): array
    {
        $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);

        if (!$words) {
            return [];
        }

        $chunks = [];
        $current = '';

        foreach ($words as $word) {
            $candidate = $current === '' ? $word : $current . ' ' . $word;

            if (mb_strlen($candidate) > $maxChars && $current !== '') {
                $chunks[] = $current;
                $current = $word;
            } else {
                $current = $candidate;
            }
        }

        if ($current !== '') {
            $chunks[] = $current;
        }

        return $chunks;
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

    private function secondsToVttTimestamp(float $seconds): string
    {
        $totalMilliseconds = max(0, (int) round($seconds * 1000));
        $hours = intdiv($totalMilliseconds, 3600000);
        $totalMilliseconds %= 3600000;
        $minutes = intdiv($totalMilliseconds, 60000);
        $totalMilliseconds %= 60000;
        $wholeSeconds = intdiv($totalMilliseconds, 1000);
        $milliseconds = $totalMilliseconds % 1000;

        return sprintf('%02d:%02d:%02d.%03d', $hours, $minutes, $wholeSeconds, $milliseconds);
    }
}
