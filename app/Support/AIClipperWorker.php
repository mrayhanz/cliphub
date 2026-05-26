<?php

namespace App\Support;

class AIClipperWorker
{
    public static function ensureRunning(bool $force = false): void
    {
        if (config('queue.default') === 'sync') {
            return;
        }

        $markerPath = storage_path('app/ai-clipper-worker-ai-clips.lock');
        $pidPath = storage_path('app/ai-clipper-worker-ai-clips.pid');

        if (!$force && self::isRunning($pidPath)) {
            @file_put_contents($markerPath, (string) time());
            return;
        }

        if (!$force && is_file($markerPath) && (time() - filemtime($markerPath)) < 20) {
            return;
        }

        @file_put_contents($markerPath, (string) time());

        $php = PHP_BINARY ?: 'php';
        $artisan = base_path('artisan');
        $arguments = [
            'queue:work',
            '--queue=ai-clips',
            '--tries=1',
            '--timeout=600',
            '--sleep=3',
            '--stop-when-empty',
        ];

        if (PHP_OS_FAMILY === 'Windows') {
            self::startWindows($php, $artisan, $arguments, $pidPath);
            return;
        }

        self::startUnix($php, $artisan, $arguments, $pidPath);
    }

    private static function startWindows(string $php, string $artisan, array $arguments, string $pidPath): void
    {
        $psArguments = array_merge([$artisan], $arguments);
        $ps = '$p = Start-Process -FilePath ' . self::psQuote($php)
            . ' -ArgumentList @(' . implode(',', array_map([self::class, 'psQuote'], $psArguments)) . ')'
            . ' -WorkingDirectory ' . self::psQuote(base_path())
            . ' -WindowStyle Hidden -PassThru; '
            . 'Set-Content -LiteralPath ' . self::psQuote($pidPath) . ' -Value $p.Id';

        $command = 'powershell -NoProfile -ExecutionPolicy Bypass -Command ' . escapeshellarg($ps);

        $handle = @popen($command, 'r');

        if (is_resource($handle)) {
            @pclose($handle);
        }
    }

    private static function startUnix(string $php, string $artisan, array $arguments, string $pidPath): void
    {
        $command = 'nohup '
            . escapeshellarg($php) . ' '
            . escapeshellarg($artisan) . ' '
            . implode(' ', array_map('escapeshellarg', $arguments))
            . ' > /dev/null 2>&1 & echo $! > ' . escapeshellarg($pidPath);

        @exec($command);
    }

    private static function isRunning(string $pidPath): bool
    {
        if (!is_file($pidPath)) {
            return false;
        }

        $pid = (int) trim((string) @file_get_contents($pidPath));

        if ($pid <= 0) {
            return false;
        }

        if (PHP_OS_FAMILY === 'Windows') {
            $output = [];
            @exec('tasklist /FI "PID eq ' . $pid . '" /NH 2>NUL', $output);

            return collect($output)->contains(fn($line) => str_contains($line, (string) $pid));
        }

        return function_exists('posix_kill') && @posix_kill($pid, 0);
    }

    private static function psQuote(string $value): string
    {
        return "'" . str_replace("'", "''", $value) . "'";
    }
}
