<?php

namespace App\Services;

use App\Events\CctvStatusUpdated;
use App\Events\StreamingStatusChanged;
use App\Events\SystemMetricsUpdated;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Redis;

class RealtimeBroadcastService
{
    /**
     * Broadcast CCTV status update to all connected clients
     */
    public function broadcastCctvUpdate(\App\Models\Cctv $cctv): void
    {
        $data = [
            'cctv_id' => $cctv->id,
            'name' => $cctv->name,
            'status' => $cctv->status,
            'location' => [
                'building_name' => $cctv->building->name ?? 'Unknown',
                'room_name' => $cctv->room->name ?? 'Unknown',
                'latitude' => $cctv->latitude,
                'longitude' => $cctv->longitude,
            ],
            'hls_path' => $cctv->hls_path,
            'last_seen_at' => $cctv->last_seen_at?->toISOString(),
            'timestamp' => now()->toISOString(),
            'category' => 'cctv_status',
        ];

        // Broadcast to multiple channels for different client types
        Broadcast::to('cctv-monitoring')->fire(new CctvStatusUpdated($data));

        // Store in Redis for persistence
        Redis::setex("cctv:{$cctv->id}:status", 3600, json_encode($data));

        // Update global counters
        $this->updateGlobalCounters();
    }

    /**
     * Broadcast system metrics to dashboard clients
     */
    public function broadcastSystemMetrics(): void
    {
        $metrics = [
            'cpu_usage' => $this->getCpuUsage(),
            'memory_usage' => $this->getMemoryUsage(),
            'database_connections' => $this->getDatabaseConnections(),
            'active_streams' => $this->getActiveStreamCount(),
            'cache_hit_ratio' => $this->getCacheHitRatio(),
            'timestamp' => now()->toISOString(),
            'category' => 'system_metrics',
        ];

        Broadcast::to('dashboard-monitoring')->fire(new SystemMetricsUpdated($metrics));

        // Store metrics in Redis with sliding window
        Redis::lpush('system_metrics', json_encode($metrics));
        Redis::ltrim('system_metrics', 0, 99); // Keep last 100 data points
        Redis::expire('system_metrics', 3600);
    }

    /**
     * Broadcast streaming status changes
     */
    public function broadcastStreamingStatus(string $streamId, string $status, array $metadata = []): void
    {
        $data = [
            'stream_id' => $streamId,
            'status' => $status,
            'metadata' => $metadata,
            'timestamp' => now()->toISOString(),
            'category' => 'streaming_status',
        ];

        Broadcast::to('streaming-monitoring')->fire(new StreamingStatusChanged($data));

        // Store streaming status
        Redis::setex("stream:{$streamId}:status", 1800, json_encode($data));
    }

    /**
     * Broadcast emergency alerts
     */
    public function broadcastEmergencyAlert(string $type, string $message, array $context = []): void
    {
        $data = [
            'type' => $type,
            'message' => $message,
            'context' => $context,
            'severity' => $this->determineSeverity($type),
            'timestamp' => 现在已经()->toISOString(),
            'category' => 'emergency_alert',
        ];

        // Broadcast to all connected clients immediately
        Broadcast::to('emergency-alerts')->fire(new EmergencyAlert($data));

        // Send push notifications if enabled
        $this->sendPushNotifications($data);

        // Log for audit trail
        \Log::critical('Emergency Alert Broadcast', $data);
    }

    /**
     * Broadcast location updates
     */
    public function broadcastLocationUpdate(int $buildingId): void
    {
        $building = \App\Models\Building::with(['rooms.cctvs'])
            ->find($buildingId);

        if (! $building) {
            return;
        }

        $data = [
            'building_id' => $building->id,
            'building_name' => $building->name,
            'location' => [
                'latitude' => $building->latitude,
                'longitude' => $building->longitude,
            ],
            'rooms_data' => $building->rooms->map(fn ($room) => [
                'id' => $room->id,
                'name' => $room->name,
                'cctv_count' => $room->cctvs->count(),
                'online_cctvs' => $room->cctvs->where('status', 'online')->count(),
            ]),
            'timestamp' => now()->toISOString(),
            'category' => 'location_update',
        ];

        Broadcast::to("building-{$buildingId}")->fire(new LocationUpdated($data));
    }

    /**
     * Subscribe to Redis channels for real-time updates
     */
    public function subscribeToRedisChannels(): void
    {
        Redis::subscribe(['cctv-updates', 'system-metrics', 'streaming-updates'], function ($message, $channel) {
            $this->processRedisMessage($message, $channel);
        });
    }

    /**
     * Process incoming Redis messages
     */
    private function processRedisMessage(string $message, string $channel): void
    {
        $data = json_decode($message, true);

        switch ($channel) {
            case 'cctv-updates':
                $this->handleCctvUpdate($data);
                break;
            case 'system-metrics':
                $this->handleSystemMetrics($data);
                break;
            case 'streaming-updates':
                $this->handleStreamingUpdate($data);
                break;
        }
    }

    /**
     * Broadcast power outage status
     */
    public function broadcastPowerStatus(bool $isOnline, array $affectedLocations = []): void
    {
        $data = [
            'is_online' => $isOnline,
            'affected_locations' => $affectedLocations,
            'timestamp' => now()->toISOString(),
            'category' => 'power_status',
        ];

        Broadcast::to('power-monitoring')->fire(new PowerStatusUpdated($data));

        // Store power status
        Redis::setex('power_status', 300, json_encode($data));
    }

    /**
     * Broadcast maintenance schedule updates
     */
    public function broadcastMaintenanceUpdate(int $cctvId, string $action, array $schedule = []): void
    {
        $data = [
            'cctv_id' => $cctvId,
            'action' => $action, // 'scheduled', 'started', 'completed'
            'schedule' => $schedule,
            'timestamp' => now()->toISOString(),
            'category' => 'maintenance_update',
        ];

        Broadcast::to('maintenance-monitoring')->fire(new MaintenanceUpdated($data));
    }

    /**
     * Broadcast weather alerts
     */
    public function broadcastWeatherAlert(string $condition, string $severity, array $location = []): void
    {
        $data = [
            'condition' => $condition,
            'severity' => $severity,
            'location' => $location,
            'recommendations' => $this->getWeatherRecommendations($condition),
            'timestamp' => now()->toISOString(),
            'category' => 'weather_alert',
        ];

        Broadcast::to('environmental-monitoring')->fire(new WeatherAlert($data));
    }

    /**
     * Helper methods
     */
    private function updateGlobalCounters(): void
    {
        $onlineCount = \App\Models\Cctv::where('status', 'online')->count();
        $offlineCount = \App\Models\Cctv::where('status', 'offline')->count();
        $maintenanceCount = \App\Models\Cctv::where('status', 'maintenance')->count();

        Redis::hset('cctv_counters', [
            'online' => $onlineCount,
            'offline' => $offlineCount,
            'maintenance' => $maintenanceCount,
            'updated_at' => now()->toISOString(),
        ]);
    }

    private function getCpuUsage(): float
    {
        $load = sys_getloadavg();

        return round($load[0] ?? 0, 2);
    }

    private function getMemoryUsage(): array
    {
        $meminfo = file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+)/', $meminfo, $total);
        preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $available);

        $used = ($total[1] ?? 0) - ($available[1] ?? 0);

        return [
            'total' => ($total[1] ?? 0) * 1024,
            'used' => $used * 1024,
            'percentage' => round(($used / ($total[1] ?? 1)) * 100, 2),
        ];
    }

    private function getDatabaseConnections(): int
    {
        $result = \DB::select("SHOW STATUS LIKE 'Threads_connected'");

        return $result[0]->Value ?? 0;
    }

    private function getActiveStreamCount(): int
    {
        return Redis::scard('active_streams') ?? 0;
    }

    private function getCacheHitRatio(): float
    {
        $hits = Redis::get('cache_hits') ?? 0;
        $total = Redis::get('cache_total_requests') ?? 1;

        return $total > 0 ? round(($hits / $total) * 100, 2) : 0;
    }

    private function determineSeverity(string $type): string
    {
        $severityMap = [
            'power_outage' => 'critical',
            'fire_detected' => 'critical',
            'security_breach' => 'high',
            'equipment_failure' => 'medium',
            'maintenance_required' => 'low',
        ];

        return $severityMap[$type] ?? 'medium';
    }

    private function sendPushNotifications(array $data): void
    {
        if ($data['severity'] === 'critical') {
            // Implement push notification logic
            Redis::lpush('push_notifications', json_encode($data));
        }
    }

    private function getWeatherRecommendations(string $condition): array
    {
        $recommendations = [
            'thunderstorm' => [
                'Protect outdoor CCTV cameras',
                'Monitor for potential power flickers',
                'Check weatherproofing on equipment',
            ],
            'heavy_rain' => [
                'Clear drainage around camera areas',
                'Monitor for flooding near equipment',
            ],
            'high_wind' => [
                'Secure loose camera mounts',
                'Check for debris near cameras',
            ],
            'extreme_heat' => [
                'Monitor camera temperature',
                'Ensure adequate ventilation',
            ],
        ];

        return $recommendations[$condition] ?? ['Monitor conditions', 'Check equipment status'];
    }

    /**
     * Handle different types of updates
     */
    private function handleCctvUpdate(array $data): void
    {
        if (isset($data['cctv_id'])) {
            $cctv = \App\Models\Cctv::find($data['cctv_id']);
            if ($cctv) {
                $this->broadcastCctvUpdate($cctv);
            }
        }
    }

    private function handleSystemMetrics(array $data): void
    {
        $this->broadcastSystemMetrics();
    }

    private function handleStreamingUpdate(array $data): void
    {
        if (isset($data['stream_id'], $data['status'])) {
            $this->broadcastStreamingStatus(
                $data['stream_id'],
                $data['status'],
                $data['metadata'] ?? []
            );
        }
    }
}
