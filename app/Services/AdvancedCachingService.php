<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class AdvancedCachingService
{
    private const CACHE_TTL = 3600; // 1 hour

    private const REDIS_TTL = 1800; // 30 minutes

    /**
     * Cache system status with advanced metrics
     */
    public function cacheSystemStatus(): array
    {
        $cacheKey = 'system_status_dashboard';

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return [
                'database' => $this->getDatabaseMetrics(),
                'redis' => $this->getRedisMetrics(),
                'ffmpeg' => $this->getFfmpegMetrics(),
                'storage' => $this->getStorageMetrics(),
                'system' => $this->getSystemMetrics(),
            ];
        });
    }

    /**
     * Cache CCTV statistics with performance optimization
     */
    public function cacheCctvStats(): array
    {
        $cacheKey = 'cctv_stats_advanced';

        $stats = Cache::remember($cacheKey, 300, function () {
            $cctvs = \App\Models\Cctv::with(['building', 'room'])
                ->selectRaw('status, COUNT(*) as count, AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as avg_response_time')
                ->groupBy('status')
                ->get();

            return [
                'by_status' => $cctvs->pluck('count', 'status'),
                'total' => $cctvs->sum('count'),
                'avg_response_time' => round($cctvs->avg('avg_response_time'), 2),
                'online_percentage' => $this->calculateOnlinePercentage($cctvs),
                'last_updated' => now()->toISOString(),
            ];
        });

        // Store in Redis for real-time updates
        Redis::setex('cctv_stats_realtime', 60, json_encode($stats));

        return $stats;
    }

    /**
     * Cache building analytics with location data
     */
    public function cacheBuildingAnalytics(): array
    {
        $cacheKey = 'building_analytics_advanced';

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return [
                'total_buildings' => \App\Models\Building::count(),
                'total_rooms' => \App\Models\Room::count(),
                'cctv_per_building' => $this->getCctvPerBuilding(),
                'regional_distribution' => $this->getRegionalDistribution(),
                'maintenance_schedule' => $this->getMaintenanceSchedule(),
                'performance_metrics' => $this->getPerformanceMetrics(),
            ];
        });
    }

    /**
     * Advanced caching for maps data
     */
    public function cacheMapsData(): array
    {
        $cacheKey = 'maps_data_optimized';

        return Cache::remember($cacheKey, 1800, function () {
            return [
                'buildings' => \App\Models\Building::with('rooms.cctvs')
                    ->select(['id', 'name', 'latitude', 'longitude'])
                    ->get()
                    ->map(function ($building) {
                        return [
                            'id' => $building->id,
                            'name' => $building->name,
                            'lat' => $building->latitude,
                            'lng' => $building->longitude,
                            'total_cctvs' => $building->rooms->sum(fn ($room) => $room->cctvs->count()),
                            'online_cctvs' => $building->rooms->sum(fn ($room) => $room->cctvs->where('status', 'online')->count()),
                        ];
                    }),
                'bounds' => $this->getMapBounds(),
                'statistics' => $this->cacheCctvStats(),
            ];
        });
    }

    /**
     * Cache streaming metrics
     */
    public function cacheStreamingMetrics(): array
    {
        $cacheKey = 'streaming_metrics_realtime';

        $metrics = Cache::remember($cacheKey, 30, function () {
            $activeStreams = $this->getActiveStreams();

            return [
                'active_streams' => count($activeStreams),
                'total_bandwidth' => array_sum(array_column($activeStreams, 'bandwidth')),
                'avg_quality' => $this->calculateAverageQuality($activeStreams),
                'cache_hit_ratio' => $this->calculateCacheHitRatio(),
                'stream_health' => $this->getStreamHealth(),
                'last_updated' => now()->toISOString(),
            ];
        });

        // Push to Redis for real-time updates
        Redis::publish('streaming_metrics', json_encode($metrics));

        return $metrics;
    }

    /**
     * Advanced query optimization with eager loading
     */
    public function optimizedCctvQuery($filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = 'optimized_cctv_query_'.md5(serialize($filters));

        return Cache::remember($cacheKey, 600, function () use ($filters) {
            return \App\Models\Cctv::with([
                'building:id,name,latitude,longitude',
                'room:id,name,latitude,longitude,building_id',
            ])
                ->when(isset($filters['status']), fn ($q) => $q->where('status', $filters['status']))
                ->when(isset($filters['building_id']), fn ($q) => $q->where('building_id', $filters['building_id']))
                ->when(isset($filters['limit']), fn ($q) => $q->limit($filters['limit']))
                ->orderBy('updated_at', 'desc')
                ->get();
        });
    }

    /**
     * Database metrics
     */
    private function getDatabaseMetrics(): array
    {
        return [
            'connection_count' => \DB::select("SHOW STATUS LIKE 'Threads_connected'")[0]->Value ?? 0,
            'query_time_avg' => \DB::select("SHOW STATUS LIKE 'Slow_queries'")[0]->Value ?? 0,
            'cache_hit_ratio' => $this->calculateDatabaseCacheHitRatio(),
            'status' => 'connected',
        ];
    }

    /**
     * Redis metrics
     */
    private function getRedisMetrics(): array
    {
        try {
            $info = Redis::info();

            return [
                'memory_used' => $info['used_memory_human'] ?? 'Unknown',
                'connected_clients' => $info['connected_clients'] ?? 0,
                'operations_per_sec' => $info['instantaneous_ops_per_sec'] ?? 0,
                'uptime' => $info['uptime_in_seconds'] ?? 0,
                'status' => 'connected',
            ];
        } catch (\Exception $e) {
            return ['status' => 'disconnected', 'error' => $e->getMessage()];
        }
    }

    /**
     * FFmpeg metrics
     */
    private function getFfmpegMetrics(): array
    {
        $activeStreams = $this->getActiveStreams();

        return [
            'active_streams' => count($activeStreams),
            'total_cpu_utilization' => $this->getFfmpegCpuUsage(),
            'memory_usage' => $this->getFfmpegMemoryUsage(),
            'status' => count($activeStreams) > 0 ? 'active' : 'idle',
        ];
    }

    /**
     * Storage metrics
     */
    private function getStorageMetrics(): array
    {
        $disk = \Storage::disk('public');

        return [
            'total_space' => $this->formatBytes(disk_total_space($disk->path(''))),
            'used_space' => $this->formatBytes(disk_total_space($disk->path('')) - disk_free_space($disk->path(''))),
            'free_space' => $this->formatBytes(disk_free_space($disk->path(''))),
            'usage_percentage' => round((1 - disk_free_space($disk->path('')) / disk_total_space($disk->path(''))) * 100, 2),
        ];
    }

    /**
     * System metrics
     */
    private function getSystemMetrics(): array
    {
        return [
            'cpu_usage' => $this->getCpuUsage(),
            'memory_usage' => $this->getMemoryUsage(),
            'load_average' => sys_getloadavg(),
            'uptime' => $this->getSystemUptime(),
        ];
    }

    /**
     * Helper methods
     */
    private function calculateOnlinePercentage($cctvs): float
    {
        $total = $cctvs->sum('count');
        $online = $cctvs->where('status', 'online')->first()?->count ?? 0;

        return $total > 0 ? round(($online / $total) * 100, 2) : 0;
    }

    private function getCctvPerBuilding(): array
    {
        return \App\Models\Building::withCount('rooms.cctvs')
            ->get(['id', 'name'])
            ->pluck('rooms_cctvs_count', 'name')
            ->toArray();
    }

    private function getRegionalDistribution(): array
    {
        return \App\Models\Building::selectRaw('latitude, longitude, COUNT(*) as count')
            ->groupBy('latitude', 'longitude')
            ->get()
            ->map(fn ($item) => [
                'lat' => (float) $item->latitude,
                'lng' => (float) $item->longitude,
                'count' => $item->count,
            ])
            ->toArray();
    }

    private function getMaintenanceSchedule(): array
    {
        return \App\Models\Cctv::where('status', 'maintenance')
            ->where('updated_at', '>=', now()->subDays(7))
            ->count();
    }

    private function getPerformanceMetrics(): array
    {
        return [
            'avg_response_time' => \App\Models\Cctv::whereNotNull('last_seen_at')
                ->avg(\DB::raw('TIMESTAMPDIFF(SECOND, created_at, updated_at)')),
            'uptime_percentage' => $this->calculateUptimePercentage(),
            'maintenance_count' => \App\Models\Cctv::where('status', 'maintenance')->count(),
        ];
    }

    private function getMapBounds(): array
    {
        return [
            'southwest' => ['lat' => -6.3785, 'lng' => 108.3200],
            'northeast' => ['lat' => -6.3620, 'lng' => 108.3385],
            'center' => ['lat' => -6.37025, 'lng' => 108.32925],
        ];
    }

    private function getActiveStreams(): array
    {
        // Mock data - implement actual FFmpeg process monitoring
        return [
            ['stream_id' => 'cctv_001', 'bandwidth' => 1024, 'quality' => 'HD'],
            ['stream_id' => 'cctv_002', 'bandwidth' => 2048, 'quality' => 'FHD'],
        ];
    }

    private function calculateAverageQuality($streams): string
    {
        if (empty($streams)) {
            return 'Unknown';
        }

        $qualityScores = ['SD' => 1, 'HD' => 2, 'FHD' => 3, '4K' => 4];
        $avgScore = array_sum(array_map(fn ($s) => $qualityScores[$s['quality']] ?? 1, $streams)) / count($streams);

        return array_search(round($avgScore), $qualityScores) ?: 'HD';
    }

    private function calculateCacheHitRatio(): float
    {
        $totalRequests = Redis::get('cache_total_requests') ?? 0;
        $cacheHits = Redis::get('cache_hits') ?? 0;

        return $totalRequests > 0 ? round(($cacheHits / $totalRequests) * 100, 2) : 0;
    }

    private function calculateDatabaseCacheHitRatio(): float
    {
        // Mock implementation
        return rand(95, 99);
    }

    private function getFfmpegCpuUsage(): string
    {
        // Mock implementation
        return rand(15, 45).'%';
    }

    private function getFfmpegMemoryUsage(): string
    {
        // Mock implementation
        return $this->formatBytes(rand(512, 2048) * 1024 * 1024);
    }

    private function getCpuUsage(): float
    {
        $load = sys_getloadavg();

        return round($load[0] * 100 / 4, 2); // Assuming 4 cores
    }

    private function getMemoryUsage(): array
    {
        $meminfo = file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+)/', $meminfo, $total);
        preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $available);

        $used = ($total[1] ?? 0) - ($available[1] ?? 0);

        return [
            'total' => $this->formatBytes(($total[1] ?? 0) * 1024),
            'used' => $this->formatBytes($used * 1024),
            'percentage' => round(($used / ($total[1] ?? 1)) * 100, 2),
        ];
    }

    private function getSystemUptime(): string
    {
        $uptime = shell_exec('uptime -p');

        return trim($uptime ?? 'Unknown');
    }

    private function calculateUptimePercentage(): float
    {
        return rand(95, 99); // Mock implementation
    }

    private function getStreamHealth(): array
    {
        return [
            'healthy_streams' => rand(70, 90),
            'degraded_streams' => rand(5, 15),
            'failed_streams' => rand(0, 5),
        ];
    }

    private function formatBytes($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return rounds($bytes, 2).' '.$units[$pow];
    }
}
