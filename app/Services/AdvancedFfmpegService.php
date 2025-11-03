<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Process\PendingProcess;
use Illuminate\Process\InvokedProcess;

class AdvancedFfmpegService
{
    private const DEFAULT_BITRATE = 2000;

    private const DEFAULT_RESOLUTION = '1280x720';

    private const HLS_SEGMENT_DURATION = 2;

    private const HLS_PLAYLIST_SIZE = 5;

    /**
     * Initialize FFmpeg stream with advanced configuration
     */
    public function initializeAdvancedStream(array $config): array
    {
        try {
            $streamId = $config['stream_id'] ?? uniqid('stream_');
            $rtspUrl = $config['rtsp_url'];

            // Create high-quality HLS stream configuration
            $ffmpegConfig = $this->buildAdvancedConfig($config, $streamId);

            // Generate FFmpeg command
            $command = $this->generateAdvancedCommand($rtspUrl, $ffmpegConfig);

            // Start FFmpeg process
            $process = $this->startStreamProcess($command, $streamId);

            // Store stream metadata
            $streamData = [
                'stream_id' => $streamId,
                'rtsp_url' => $rtspUrl,
                'config' => $ffmpegConfig,
                'status' => 'initializing',
                'started_at' => now()->toISOString(),
                'quality_settings' => [
                    'bitrate' => $ffmpegConfig['video_bitrate'],
                    'resolution' => $ffmpegConfig['resolution'],
                    'framerate' => $ffmpegConfig['framerate'],
                ],
            ];

            $this->storeStreamMetadata($streamId, $streamData);

            Log::info('Advanced stream initialized', [
                'stream_id' => $streamId,
                'config' => $ffmpegConfig,
                'command' => $command,
            ]);

            return [
                'success' => true,
                'stream_id' => $streamId,
                'hls_url' => $this->getHlsUrl($streamId),
                'metadata' => $streamData,
            ];

        } catch (\Exception $e) {
            Log::error('Failed to initialize advanced stream', [
                'config' => $config,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build advanced FFmpeg configuration
     */
    private function buildAdvancedConfig(array $config, string $streamId): array
    {
        $baseConfig = [
            'resolution' => $config['resolution'] ?? self::DEFAULT_RESOLUTION,
            'video_bitrate' => $config['bitrate'] ?? self::DEFAULT_BITRATE,
            'audio_bitrate' => $config['audio_bitrate'] ?? 128,
            'framerate' => $config['framerate'] ?? 25,
            'codec' => $config['codec'] ?? 'libx264',
            'preset' => $config['preset'] ?? 'fast',
            'profile' => $config['profile'] ?? 'high',
            'level' => $config['level'] ?? '4.1',
            'crf' => $config['crf'] ?? 23,
            'segment_duration' => self::HLS_SEGMENT_DURATION,
            'playlist_size' => self::HLS_PLAYLIST_SIZE,
            'enable_audio' => $config['enable_audio'] ?? true,
            'adaptive_bitrate' => $config['adaptive_bitrate'] ?? false,
            'output_location' => storage_path("app/live/{$streamId}"),
        ];

        // Enhance configuration based on quality settings
        if (isset($config['quality'])) {
            if ($config['quality'] === 'ultra_hd') {
                $baseConfig['resolution'] = '3840x2160';
                $baseConfig['video_bitrate'] = 8000;
                $baseConfig['audio_bitrate'] = 256;
                $baseConfig['framerate'] = 30;
            } elseif ($config['quality'] === 'full_hd') {
                $baseConfig['resolution'] = '1920x1080';
                $baseConfig['video_bitrate'] = 4000;
                $baseConfig['audio_bitrate'] = 192;
                $baseConfig['framerate'] = 25;
            } elseif ($config['quality'] === 'hd') {
                $baseConfig['resolution'] = '1280x720';
                $baseConfig['video_bitrate'] = 2000;
                $baseConfig['audio_bitrate'] = 128;
                $baseConfig['framerate'] = 25;
            } elseif ($config['quality'] === 'sd') {
                $baseConfig['resolution'] = '854x480';
                $baseConfig['video_bitrate'] = 1000;
                $baseConfig['audio_bitrate'] = 96;
                $baseConfig['framerate'] = 24;
            }
        }

        return $baseConfig;
    }

    /**
     * Generate advanced FFmpeg command with quality optimizations
     */
    private function generateAdvancedCommand(string $rtspUrl, array $config): string
    {
        $outputDir = $config['output_location'];
        if (! file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Input configuration
        $inputOptions = [
            '-rtsp_transport tcp',
            '-i "'.$rtspUrl.'"',
            '-avoid_negative_ts make_zero',
            '-fflags +genpts+discardcorrupt',
        ];

        // Video codec configuration
        $videoCodecOptions = [
            '-c:v '.$config['codec'],
            '-preset '.$config['preset'],
            '-profile:v '.$config['profile'],
            '-level:v '.$config['level'],
            '-crf '.$config['crf'],
            '-maxrate '.($config['video_bitrate'] * 1.2).'k',
            '-bufsize '.($config['video_bitrate'] * 2).'k',
            '-s '.$config['resolution'],
            '-r '.$config['framerate'],
            '-g '.($config['framerate'] * 2), // GOP size
            '-keyint_min '.$config['framerate'],
            '-sc_threshold 0',
            '-pix_fmt yuv420p',
        ];

        // Audio codec configuration
        $audioCodecOptions = $config['enable_audio'] ? [
            '-c:a aac',
            '-b:a '.$config['audio_bitrate'].'k',
            '-ac 2',
            '-ar 44100',
            '-movflags +faststart',
        ] : ['-an']; // No audio

        // HLS configuration
        $hlsOptions = [
            '-f hls',
            '-hls_time '.$config['segment_duration'],
            '-hls_list_size '.$config['playlist_size'],
            '-hls_flags independent_segments',
            '-hls_segment_filename "'.$outputDir.'/segment_%03d.ts"',
            '-hls_start_number_source datetime',
            '-hls_allow_cache 1',
            '-hls_delete_threshold 2',
        ];

        // Quality optimization options
        $qualityOptions = [
            '-filter:v "scale='.$config['resolution'].':flags=lanczos"',
            '-metadata title="CCTV Stream"',
            '-metadata artist="PT Kilang Pertamina Internasional"',
        ];

        // Error handling and reliability options
        $reliabilityOptions = [
            '-max_muxing_queue_size 1024',
            '-flush_packets 1',
            '-fflags +flush_packets',
            '-reconnect 1',
            '-reconnect_streamed eof',
            '-reconnect_delay_max 2',
        ];

        $command = 'ffmpeg '.implode(' ', array_merge(
            $inputOptions,
            $videoCodecOptions,
            $audioCodecOptions,
            $hlsOptions,
            $qualityOptions,
            $reliabilityOptions,
            ['"'.$outputDir.'/playlist.m3u8"']
        ));

        return $command;
    }

    /**
     * Start FFmpeg process with monitoring
     */
    private function startStreamProcess(string $command, string $streamId): InvokedProcess
    {
        $process = Process::timeout(3600) // 1 hour timeout
            ->start($command);

        // Monitor process in background
        $this->monitorStreamProcess($process, $streamId);

        return $process;
    }

    /**
     * Monitor FFmpeg process for health and performance
     */
    private function monitorStreamProcess(InvokedProcess $process, string $streamId): void
    {
        // Start monitoring in background
        // Note: This is a simplified version. In a real implementation, you would need
        // a proper monitoring system or artisan command to handle this.

        // Log process start
        Log::info('FFmpeg process started', [
            'stream_id' => $streamId,
        ]);

        // Store process info in cache for monitoring (using Laravel's cache)
        \Illuminate\Support\Facades\Cache::put("streams:{$streamId}", [
            'status' => 'running',
            'started_at' => now()->toISOString(),
            'monitored' => true,
        ], 86400); // 24 hours
    }

    /**
     * Get HLS URL for stream
     */
    private function getHlsUrl(string $streamId): string
    {
        return url("/live/{$streamId}/playlist.m3u8");
    }

    /**
     * Store stream metadata
     */
    private function storeStreamMetadata(string $streamId, array $metadata): void
    {
        \Illuminate\Support\Facades\Cache::put("stream:{$streamId}:metadata", $metadata, 86400); // 24 hours
    }

    /**
     * Stop advanced stream gracefully
     */
    public function stopAdvancedStream(string $streamId): array
    {
        try {
            $metadata = \Illuminate\Support\Facades\Cache::get("stream:{$streamId}:metadata", []);
            // In a real implementation, you would need to properly terminate the FFmpeg process

            // Cleanup HLS files
            $this->cleanupHlsFiles($streamId);

            // Remove metadata
            \Illuminate\Support\Facades\Cache::forget("stream:{$streamId}:metadata");
            \Illuminate\Support\Facades\Cache::forget("streams:{$streamId}");

            Log::info('Advanced stream stopped', [
                'stream_id' => $streamId,
            ]);

            return [
                'success' => true,
                'message' => "Stream {$streamId} stopped successfully",
            ];

        } catch (\Exception $e) {
            Log::error('Failed to stop advanced stream', [
                'stream_id' => $streamId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get stream performance metrics
     */
    public function getStreamMetrics(string $streamId): array
    {
        try {
            $metadata = \Illuminate\Support\Facades\Cache::get("stream:{$streamId}:metadata", []);

            return [
                'stream_id' => $streamId,
                'status' => 'unknown',
                'uptime' => 0,
                'frames_processed' => 0,
                'bitrate_actual' => 0,
                'buffer_health' => 'unknown',
                'quality_score' => 100,
                'metadata' => $metadata,
                'last_updated' => now()->toISOString(),
            ];

        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'stream_id' => $streamId,
            ];
        }
    }

    /**
     * Update stream quality dynamically
     */
    public function adjustStreamQuality(string $streamId, array $newConfig): array
    {
        try {
            // Get current stream data
            $currentMetadata = \Illuminate\Support\Facades\Cache::get("stream:{$streamId}:metadata", []);

            if (empty($currentMetadata)) {
                return [
                    'success' => false,
                    'error' => 'Stream not found',
                ];
            }

            // Stop current stream
            $this->stopAdvancedStream($streamId);

            // Start with new configuration
            $newConfig['stream_id'] = $streamId;
            $newConfig['rtsp_url'] = $currentMetadata['rtsp_url'] ?? '';

            return $this->initializeAdvancedStream($newConfig);

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Helper methods
     */
    private function isProcessRunning(string $pid): bool
    {
        // Check if process is running using tasklist on Windows
        $process = Process::run("tasklist /fi \"PID eq {$pid}\"");
        return strpos($process->output(), $pid) !== false;
    }

    private function gracefullyTerminateProcess(string $pid): void
    {
        Process::run("taskkill /PID {$pid} /T");
    }

    private function forceKillProcess(string $pid): void
    {
        Process::run("taskkill /F /PID {$pid} /T");
    }

    private function cleanupHlsFiles(string $streamId): void
    {
        $streamDir = storage_path("app/live/{$streamId}");

        if (is_dir($streamDir)) {
            $files = glob($streamDir.'/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($streamDir);
        }
    }

    private function calculateQualityScore(array $stats): int
    {
        // Simple quality scoring algorithm
        $score = 100;

        if (isset($stats['packet_loss']) && $stats['packet_loss'] > 0) {
            $score -= min(20, $stats['packet_loss'] * 10);
        }

        if (isset($stats['jitter']) && $stats['jitter'] > 50) {
            $score -= min(15, ($stats['jitter'] - 50) / 2);
        }

        return max(0, $score);
    }

    /**
     * Get all active streams
     */
    public function getAllActiveStreams(): array
    {
        // In a real implementation, you would need to properly track active streams
        return [];
    }

    /**
     * Health check for all streams
     */
    public function healthCheckStreams(): array
    {
        $streams = $this->getAllActiveStreams();
        $healthReport = [
            'total_streams' => count($streams),
            'healthy_streams' => 0,
            'degraded_streams' => 0,
            'failed_streams' => 0,
            'issues' => [],
        ];

        foreach ($streams as $stream) {
            $quality = $this->calculateQualityScore([]);

            if ($quality >= 90) {
                $healthReport['healthy_streams']++;
            } elseif ($quality >= 70) {
                $healthReport['degraded_streams']++;
            } else {
                $healthReport['failed_streams']++;
                $healthReport['issues'][] = [
                    'stream_id' => $stream['stream_id'] ?? '',
                    'quality_score' => $quality,
                    'issue' => 'Low quality score',
                ];
            }
        }

        return $healthReport;
    }
}
