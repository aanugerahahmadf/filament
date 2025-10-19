<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Cctv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HealthCheckService
{
    public function getSystemHealth(): array
    {
        return [
            'application' => $this->checkApplicationHealth(),
            'database' => $this->checkDatabaseHealth(),
            'storage' => $this->checkStorageHealth(),
            'cctvs' => $this->checkCctvHealth(),
            'services' => $this->checkServiceHealth(),
        ];
    }

    protected function checkApplicationHealth(): array
    {
        return [
            'status' => 'healthy',
            'version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'uptime' => now()->diffInSeconds(now()->subSeconds(microtime(true) - LARAVEL_START)),
        ];
    }

    protected function checkDatabaseHealth(): array
    {
        try {
            DB::connection()->getPdo();
            $status = 'healthy';
            $error = null;
        } catch (\Exception $e) {
            $status = 'unhealthy';
            $error = $e->getMessage();
        }

        return [
            'status' => $status,
            'error' => $error,
            'connection' => config('database.default'),
        ];
    }

    protected function checkStorageHealth(): array
    {
        try {
            Storage::disk('local')->put('health_check.txt', 'OK');
            Storage::disk('local')->delete('health_check.txt');
            $status = 'healthy';
            $error = null;
        } catch (\Exception $e) {
            $status = 'unhealthy';
            $error = $e->getMessage();
        }

        return [
            'status' => $status,
            'error' => $error,
            'disk_space' => $this->getDiskSpace(),
        ];
    }

    protected function checkCctvHealth(): array
    {
        $total = Cctv::count();
        $online = Cctv::online()->count();
        $offline = Cctv::offline()->count();
        $maintenance = Cctv::maintenance()->count();

        $onlinePercentage = $total > 0 ? ($online / $total) * 100 : 0;

        $status = 'healthy';
        if ($onlinePercentage < 50) {
            $status = 'critical';
        } elseif ($onlinePercentage < 80) {
            $status = 'warning';
        }

        return [
            'status' => $status,
            'total' => $total,
            'online' => $online,
            'offline' => $offline,
            'maintenance' => $maintenance,
            'online_percentage' => round($onlinePercentage, 2),
        ];
    }

    protected function checkServiceHealth(): array
    {
        $services = [
            'ffmpeg' => $this->checkFfmpeg(),
            'ffprobe' => $this->checkFfprobe(),
            'redis' => $this->checkRedis(),
        ];

        $unhealthyCount = collect($services)->filter(fn ($service) => $service['status'] === 'unhealthy')->count();

        $status = 'healthy';
        if ($unhealthyCount > 0) {
            $status = 'warning';
        }

        return [
            'status' => $status,
            'services' => $services,
        ];
    }

    protected function checkFfmpeg(): array
    {
        try {
            $ffmpeg = config('services.ffmpeg.binary', 'ffmpeg');
            $process = new \Symfony\Component\Process\Process([$ffmpeg, '-version']);
            $process->run();

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                preg_match('/ffmpeg version ([^\s]+)/', $output, $matches);
                $version = $matches[1] ?? 'unknown';

                return [
                    'status' => 'healthy',
                    'version' => $version,
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'error' => 'FFmpeg not found or not working',
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function checkFfprobe(): array
    {
        try {
            $ffprobe = config('services.ffprobe.binary', 'ffprobe');
            $process = new \Symfony\Component\Process\Process([$ffprobe, '-version']);
            $process->run();

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                preg_match('/ffprobe version ([^\s]+)/', $output, $matches);
                $version = $matches[1] ?? 'unknown';

                return [
                    'status' => 'healthy',
                    'version' => $version,
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'error' => 'FFprobe not found or not working',
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function checkRedis(): array
    {
        try {
            if (config('queue.default') === 'redis') {
                $redis = app()->make('redis');
                $redis->ping();

                return [
                    'status' => 'healthy',
                ];
            } else {
                return [
                    'status' => 'disabled',
                    'reason' => 'Redis not configured as queue driver',
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function getDiskSpace(): array
    {
        $freeSpace = disk_free_space(base_path());
        $totalSpace = disk_total_space(base_path());
        $usedSpace = $totalSpace - $freeSpace;
        $percentage = $totalSpace > 0 ? ($usedSpace / $totalSpace) * 100 : 0;

        return [
            'total' => $totalSpace,
            'free' => $freeSpace,
            'used' => $usedSpace,
            'percentage' => round($percentage, 2),
        ];
    }

    public function getAlertsSummary(): array
    {
        $total = Alert::count();
        $active = Alert::active()->count();
        $critical = Alert::ofSeverity(Alert::SEVERITY_CRITICAL)->count();
        $high = Alert::ofSeverity(Alert::SEVERITY_HIGH)->count();

        return [
            'total' => $total,
            'active' => $active,
            'critical' => $critical,
            'high' => $high,
        ];
    }
}
