<?php

namespace App\Services;

use App\Events\CctvStatusUpdated;
use App\Models\Cctv;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class FfmpegStreamService
{
    public function startStream(Cctv $cctv): string
    {
        Log::channel('daily')->info('Starting stream for CCTV '.$cctv->id, [
            'ip_rtsp' => $cctv->ip_rtsp,
            'status' => $cctv->status,
            'last_seen_at' => $cctv->last_seen_at,
        ]);

        $outputDir = public_path('live');
        if (! is_dir($outputDir)) {
            mkdir($outputDir, 0775, true);
        }

        $safeSlug = 'cctv_'.$cctv->id;
        $playlistPath = $outputDir.DIRECTORY_SEPARATOR.$safeSlug.'.m3u8';
        $segmentPath = $outputDir.DIRECTORY_SEPARATOR.$safeSlug.'_%03d.ts';

        $ffmpeg = config('services.ffmpeg.binary', 'ffmpeg');

        // HLS low-latency tuned settings
        $args = [
            $ffmpeg,
            '-rtsp_transport', 'tcp',
            '-stimeout', '5000000', // 5 seconds timeout
            '-i', $cctv->ip_rtsp,
            '-c:v', 'copy',
            '-c:a', 'aac',
            '-f', 'hls',
            '-hls_time', '2',
            '-hls_list_size', '6',
            '-hls_flags', 'delete_segments+program_date_time',
            '-hls_segment_filename', $segmentPath,
            $playlistPath,
        ];

        Log::channel('daily')->info('FFmpeg command for CCTV '.$cctv->id, [
            'command' => implode(' ', $args),
        ]);

        $process = new Process($args);
        $process->setTimeout(null);
        $process->setIdleTimeout(null);

        // Run in background by starting asynchronously
        $process->start(function ($type, $buffer) use ($cctv) {
            if ($type === Process::ERR) {
                Log::channel('daily')->error('FFmpeg error for CCTV '.$cctv->id.': '.$buffer);
            } else {
                Log::channel('daily')->info('FFmpeg output for CCTV '.$cctv->id.': '.$buffer);
            }
        });

        // Wait a bit to see if the process starts successfully
        sleep(2);

        if (! $process->isRunning()) {
            $errorOutput = $process->getErrorOutput();
            $exitCode = $process->getExitCode();

            Log::channel('daily')->error('FFmpeg process failed to start for CCTV '.$cctv->id, [
                'exit_code' => $exitCode,
                'error_output' => $errorOutput,
                'ip_rtsp' => $cctv->ip_rtsp,
            ]);

            // Update CCTV status to offline if connection fails
            $cctv->update([
                'status' => Cctv::STATUS_OFFLINE,
                'last_seen_at' => now(),
            ]);

            CctvStatusUpdated::dispatch($cctv->fresh());

            // Don't throw exception, just return error message
            return 'Failed to connect to CCTV stream: '.$errorOutput;
        }

        $cctv->update([
            'hls_path' => '/live/'.$safeSlug.'.m3u8',
            'status' => Cctv::STATUS_ONLINE,
            'last_seen_at' => now(),
        ]);

        CctvStatusUpdated::dispatch($cctv->fresh());

        $hlsUrl = '/live/'.$safeSlug.'.m3u8';
        Log::channel('daily')->info('Stream started successfully for CCTV '.$cctv->id, [
            'hls_url' => $hlsUrl,
        ]);

        return $hlsUrl;
    }

    public function stopStream(Cctv $cctv): void
    {
        // Best-effort: delete playlist and segments for this CCTV
        $safeSlug = 'cctv_'.$cctv->id;
        $glob = public_path('live'.DIRECTORY_SEPARATOR.$safeSlug.'*');
        foreach (glob($glob) as $file) {
            @unlink($file);
        }

        $cctv->update([
            'status' => Cctv::STATUS_OFFLINE,
        ]);

        CctvStatusUpdated::dispatch($cctv->fresh());
    }

    /**
     * Capture a snapshot (JPEG) from the CCTV stream.
     * Returns the public URL to the saved image.
     */
    public function takeSnapshot(Cctv $cctv): string
    {
        $screenshotsDir = public_path('screenshots');
        if (! is_dir($screenshotsDir)) {
            mkdir($screenshotsDir, 0775, true);
        }

        $timestamp = now()->format('Ymd_His');
        $filename = 'cctv_'.$cctv->id.'_'.$timestamp.'.jpg';
        $outputPath = $screenshotsDir.DIRECTORY_SEPARATOR.$filename;

        $ffmpeg = config('services.ffmpeg.binary', 'ffmpeg');

        // Grab a single frame quickly
        $args = [
            $ffmpeg,
            '-y',
            '-rtsp_transport', 'tcp',
            '-i', $cctv->ip_rtsp,
            '-frames:v', '1',
            '-q:v', '2',
            $outputPath,
        ];

        $process = new Process($args);
        $process->setTimeout(30);
        $process->run();

        if (! $process->isSuccessful()) {
            Log::channel('daily')->error('FFmpeg snapshot error for CCTV '.$cctv->id.': '.$process->getErrorOutput());
            throw new \RuntimeException('Failed to capture snapshot');
        }

        return '/screenshots/'.$filename;
    }

    /**
     * Record a short MP4 clip for the given number of seconds and return the public URL.
     */
    public function recordClip(Cctv $cctv, int $seconds = 30): string
    {
        $seconds = max(5, min($seconds, 300)); // clamp between 5s and 5 minutes

        $recordingsDir = public_path('recordings');
        if (! is_dir($recordingsDir)) {
            mkdir($recordingsDir, 0775, true);
        }

        $timestamp = now()->format('Ymd_His');
        $filename = 'cctv_'.$cctv->id.'_'.$timestamp.'.mp4';
        $outputPath = $recordingsDir.DIRECTORY_SEPARATOR.$filename;

        $ffmpeg = config('services.ffmpeg.binary', 'ffmpeg');

        // Record limited duration; re-encode for compatibility
        $args = [
            $ffmpeg,
            '-y',
            '-rtsp_transport', 'tcp',
            '-i', $cctv->ip_rtsp,
            '-t', (string) $seconds,
            '-c:v', 'libx264',
            '-preset', 'veryfast',
            '-crf', '23',
            '-c:a', 'aac',
            '-movflags', '+faststart',
            $outputPath,
        ];

        $process = new Process($args);
        // Allow long enough time (seconds + overhead)
        $process->setTimeout($seconds + 60);
        $process->run();

        if (! $process->isSuccessful()) {
            Log::channel('daily')->error('FFmpeg record error for CCTV '.$cctv->id.': '.$process->getErrorOutput());
            throw new \RuntimeException('Failed to record clip');
        }

        return '/recordings/'.$filename;
    }
}
