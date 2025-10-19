<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Cctv;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SystemMonitoringService
{
    protected CacheService $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getSystemMetrics(): array
    {
        return $this->cacheService->remember('system_metrics', 5, function () {
            return [
                'cpu_usage' => $this->getCpuUsage(),
                'memory_usage' => $this->getMemoryUsage(),
                'disk_usage' => $this->getDiskUsage(),
                'database_size' => $this->getDatabaseSize(),
                'storage_usage' => $this->getStorageUsage(),
                'network_latency' => $this->getNetworkLatency(),
                'uptime' => $this->getUptime(),
            ];
        });
    }

    protected function getCpuUsage(): float
    {
        // On Windows, we can use wmic to get CPU usage
        if (PHP_OS_FAMILY === 'Windows') {
            try {
                $output = shell_exec('wmic cpu get loadpercentage 2>&1');
                if (preg_match('/(\d+)/', $output, $matches)) {
                    return (float) $matches[1];
                }
            } catch (\Exception $e) {
                Log::warning('Failed to get CPU usage: '.$e->getMessage());
            }
        }

        // Fallback to a random value for demonstration
        return rand(10, 80);
    }

    protected function getMemoryUsage(): array
    {
        // On Windows, we can use wmic to get memory info
        if (PHP_OS_FAMILY === 'Windows') {
            try {
                $output = shell_exec('wmic OS get TotalVisibleMemorySize,FreePhysicalMemory 2>&1');
                if (preg_match('/TotalVisibleMemorySize\s+FreePhysicalMemory\s+(\d+)\s+(\d+)/', $output, $matches)) {
                    $total = (int) $matches[1];
                    $free = (int) $matches[2];
                    $used = $total - $free;
                    $percentage = $total > 0 ? ($used / $total) * 100 : 0;

                    return [
                        'total' => $total * 1024, // Convert KB to bytes
                        'used' => $used * 1024,
                        'free' => $free * 1024,
                        'percentage' => round($percentage, 2),
                    ];
                }
            } catch (\Exception $e) {
                Log::warning('Failed to get memory usage: '.$e->getMessage());
            }
        }

        // Fallback to mock data
        $total = 8 * 1024 * 1024 * 1024; // 8GB
        $used = rand(2, 6) * 1024 * 1024 * 1024; // 2-6GB
        $free = $total - $used;
        $percentage = ($used / $total) * 100;

        return [
            'total' => $total,
            'used' => $used,
            'free' => $free,
            'percentage' => round($percentage, 2),
        ];
    }

    protected function getDiskUsage(): array
    {
        $total = disk_total_space(base_path());
        $free = disk_free_space(base_path());
        $used = $total - $free;
        $percentage = $total > 0 ? ($used / $total) * 100 : 0;

        return [
            'total' => $total,
            'used' => $used,
            'free' => $free,
            'percentage' => round($percentage, 2),
        ];
    }

    protected function getDatabaseSize(): string
    {
        try {
            // For SQLite
            if (config('database.default') === 'sqlite') {
                $path = config('database.connections.sqlite.database');
                if (file_exists($path)) {
                    return $this->formatBytes(filesize($path));
                }
            }

            // For MySQL/PostgreSQL, you would need to query the database
            // This is a simplified example
            return 'Unknown';
        } catch (\Exception $e) {
            Log::warning('Failed to get database size: '.$e->getMessage());

            return 'Unknown';
        }
    }

    protected function getStorageUsage(): array
    {
        try {
            $files = Storage::allFiles();
            $totalSize = 0;

            foreach ($files as $file) {
                $totalSize += Storage::size($file);
            }

            return [
                'total_files' => count($files),
                'total_size' => $totalSize,
                'total_size_formatted' => $this->formatBytes($totalSize),
            ];
        } catch (\Exception $e) {
            Log::warning('Failed to get storage usage: '.$e->getMessage());

            return [
                'total_files' => 0,
                'total_size' => 0,
                'total_size_formatted' => '0 B',
            ];
        }
    }

    protected function getNetworkLatency(): float
    {
        // This would typically ping a known host
        // For now, we'll return a mock value
        return rand(1, 100);
    }

    protected function getUptime(): string
    {
        // This would typically get system uptime
        // For now, we'll return a mock value
        return '7 days, 3 hours, 15 minutes';
    }

    public function getCctvHealth(): array
    {
        return $this->cacheService->remember('cctv_health', 5, function () {
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
        });
    }

    public function getAlertSummary(): array
    {
        return $this->cacheService->remember('alert_summary', 2, function () {
            return [
                'total' => Alert::count(),
                'active' => Alert::active()->count(),
                'critical' => Alert::ofSeverity(Alert::SEVERITY_CRITICAL)->count(),
                'high' => Alert::ofSeverity(Alert::SEVERITY_HIGH)->count(),
            ];
        });
    }

    public function checkSystemHealth(): array
    {
        $systemMetrics = $this->getSystemMetrics();
        $cctvHealth = $this->getCctvHealth();
        $alertSummary = $this->getAlertSummary();

        $overallStatus = 'healthy';
        if ($cctvHealth['status'] === 'critical' || $alertSummary['critical'] > 0) {
            $overallStatus = 'critical';
        } elseif ($cctvHealth['status'] === 'warning' || $alertSummary['high'] > 5) {
            $overallStatus = 'warning';
        }

        return [
            'overall_status' => $overallStatus,
            'system_metrics' => $systemMetrics,
            'cctv_health' => $cctvHealth,
            'alert_summary' => $alertSummary,
        ];
    }

    protected function formatBytes(int $size, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision).' '.$units[$i];
    }

    public function clearMonitoringCache(): void
    {
        $keys = [
            'system_metrics',
            'cctv_health',
            'alert_summary',
        ];

        foreach ($keys as $key) {
            $this->cacheService->forget($key);
        }
    }
}
