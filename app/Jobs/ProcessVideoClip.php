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
        $clip = $this->clip;
        $clip->update(['status' => 'processing']);

        try {
            $outputDir  = storage_path('app/public/clips');
            if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);
            $outputFile = $outputDir . '/' . $clip->id . '.mp4';

            $ytdlpBin  = $this->findBinary('yt-dlp');
            $ffmpegBin = $this->findBinary('ffmpeg');

            // 1. Get Direct Stream URL via yt-dlp
            Log::info("ClipHub: Getting stream URL for clip #{$clip->id}");

            // Support optional cookies file for YouTube bot detection bypass
            $cookiesFile = env('YT_COOKIES_PATH', storage_path('app/yt-cookies.txt'));
            $cookieArg   = (file_exists($cookiesFile)) ? '--cookies ' . escapeshellarg($cookiesFile) : '';

            $duration = max(1, $clip->end_time - $clip->start_time);
            Log::info("Clipfluence: Downloading section with yt-dlp natively. Start: {$clip->start_time}s, Duration: {$duration}s, Ratio: {$clip->ratio}, Captions: " . ($clip->has_captions ? 'Yes' : 'No'));

            $tempDir = storage_path('app/tmp_subs');
            if (!is_dir($tempDir)) @mkdir($tempDir, 0755, true);

            $tempInputVideo = escapeshellarg($tempDir) . '/tempvid_' . $clip->id . '_' . uniqid() . '.mp4';
            $tempInputVideo = str_replace(["'\"'"], '', $tempInputVideo); // safety
            $tempInputVideo = $tempDir . '/tempvid_' . $clip->id . '_' . uniqid() . '.mp4';

            $ytdlpCmd = sprintf(
                '%s --ffmpeg-location %s %s --no-warnings -f "bv[ext=mp4]+ba[ext=m4a]/b[ext=mp4]/b" --force-keyframes-at-cuts --download-sections %s -o %s %s 2>&1',
                escapeshellarg($ytdlpBin),
                escapeshellarg($ffmpegBin),
                $cookieArg,
                escapeshellarg('*' . $clip->start_time . '-' . $clip->end_time),
                escapeshellarg($tempInputVideo),
                escapeshellarg($clip->source_url)
            );

            exec($ytdlpCmd, $ytOutput, $ytCode);
            
            // Periksa apakah hasil potongan pertama berhasil diunduh ke disk
            if (!file_exists($tempInputVideo)) {
                throw new \Exception("yt-dlp gagal mendownload/memotong stream video.");
            }

            // 2. Proses FFmpeg secara lokal (Crop & Styling)
            $videoFilters = [];
            $vttPathToCleanup = null;

            // Handle Aspect Ratio (TikTok / Reels / Shorts 9:16)
            if ($clip->ratio === '9:16') {
                $videoFilters[] = "crop=ih*9/16:ih:iw/2-ih*9/32:0,scale=1080:1920";
            }

            // Handle AI Auto-Captions (Hardsubbing)
            if ($clip->has_captions) {
                $vttName = 'vid_' . $clip->id . '_' . uniqid();
                $vttBasePattern = escapeshellarg($tempDir . '/' . $vttName . '.%(ext)s');
                
                $ytdlpSubCmd = sprintf(
                    '%s --write-auto-subs --write-subs --sub-lang id,en --skip-download --sub-format vtt -o %s %s 2>&1',
                    escapeshellarg($ytdlpBin),
                    $vttBasePattern,
                    escapeshellarg($clip->source_url)
                );
                
                exec($ytdlpSubCmd);
                
                $files = glob($tempDir . '/' . $vttName . '*');
                if (!empty($files)) {
                    $vttPathToCleanup = $files[0];
                    // FFmpeg subtitles syntax requires escaped absolute paths on Windows
                    $escapedPath = str_replace('\\', '/', $vttPathToCleanup);
                    $escapedPath = str_replace(':', '\\\\:', $escapedPath);
                    
                    // Styling the subtitles to look cool (Cyan text, black outline, bold)
                    $subStyle = "force_style='FontSize=24,PrimaryColour=&H00FFFF&,OutlineColour=&H000000&,Outline=2,Shadow=0,MarginV=40,Bold=-1'";
                    $videoFilters[] = "subtitles={$escapedPath}:{$subStyle}";
                } else {
                    Log::warning("Clipfluence: Gagal mengunduh subtitle untuk Video {$clip->source_url}");
                }
            }

            $vfArgument = '';
            if (!empty($videoFilters)) {
                $vfArgument = '-vf ' . escapeshellarg(implode(',', $videoFilters));
            }

            // FFmpeg merender dari input disk lokal sehingga bebas dari blokir 403 HTTP Google!
            $ffmpegCmd = sprintf(
                '%s -y -i %s %s -c:v libx264 -c:a aac -preset fast -movflags +faststart %s 2>&1',
                escapeshellarg($ffmpegBin),
                escapeshellarg($tempInputVideo),
                $vfArgument,
                escapeshellarg($outputFile)
            );

            exec($ffmpegCmd, $ffOutput, $ffCode);

            // Cleanup Local Temp MP4
            if (file_exists($tempInputVideo)) {
                @unlink($tempInputVideo);
            }

            // Cleanup VTT
            if ($vttPathToCleanup && file_exists($vttPathToCleanup)) {
                @unlink($vttPathToCleanup);
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
            $clip->update(['status' => 'failed']);
        }
    }

    private function findBinary(string $name): string
    {
        // 1. Cek konfigurasi di .env (Paling Tinggi Prioritasnya)
        $envKey = strtoupper(str_replace('-', '', $name)) . '_BIN_PATH';
        $envPath = env($envKey);

        if ($envPath && file_exists($envPath)) {
            return $envPath;
        }

        // 2. Fallback untuk versi Windows lokal developer (WinGet default)
        $wingetPath = 'C:\\Users\\USER\\AppData\\Local\\Microsoft\\WinGet\\Links\\' . $name . '.exe';
        if (file_exists($wingetPath)) {
            return $wingetPath;
        }

        // 3. Fallback sistem (Asumsi sudah ada di system PATH global di Linux / Hosting)
        return escapeshellarg($name);
    }
}
