<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Building;
use App\Models\Cctv;
use App\Models\Maintenance;
use App\Models\Recording;
use App\Models\Room;
use App\Models\User;

class DashboardWidgetService
{
    protected CacheService $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getCctvStats(): array
    {
        return $this->cacheService->remember('cctv_stats', 5, function () {
            $total = Cctv::count();
            $online = Cctv::online()->count();
            $offline = Cctv::offline()->count();
            $maintenance = Cctv::maintenance()->count();

            return [
                'total' => $total,
                'online' => $online,
                'offline' => $offline,
                'maintenance' => $maintenance,
                'online_percentage' => $total > 0 ? round(($online / $total) * 100, 2) : 0,
                'offline_percentage' => $total > 0 ? round(($offline / $total) * 100, 2) : 0,
                'maintenance_percentage' => $total > 0 ? round(($maintenance / $total) * 100, 2) : 0,
            ];
        });
    }

    public function getInfrastructureStats(): array
    {
        return $this->cacheService->remember('infrastructure_stats', 10, function () {
            return [
                'buildings' => Building::count(),
                'rooms' => Room::count(),
                'users' => User::count(),
            ];
        });
    }

    public function getMaintenanceStats(): array
    {
        return $this->cacheService->remember('maintenance_stats', 10, function () {
            return [
                'total' => Maintenance::count(),
                'scheduled' => Maintenance::scheduled()->count(),
                'in_progress' => Maintenance::inProgress()->count(),
                'completed' => Maintenance::completed()->count(),
                'cancelled' => Maintenance::cancelled()->count(),
                'total_cost' => Maintenance::sum('cost'),
            ];
        });
    }

    public function getAlertStats(): array
    {
        return $this->cacheService->remember('alert_stats', 5, function () {
            return [
                'total' => Alert::count(),
                'active' => Alert::active()->count(),
                'acknowledged' => Alert::acknowledged()->count(),
                'resolved' => Alert::resolved()->count(),
                'suppressed' => Alert::suppressed()->count(),
                'critical' => Alert::ofSeverity(Alert::SEVERITY_CRITICAL)->count(),
                'high' => Alert::ofSeverity(Alert::SEVERITY_HIGH)->count(),
            ];
        });
    }

    public function getRecentAlerts(int $limit = 5): array
    {
        return $this->cacheService->remember("recent_alerts_{$limit}", 2, function () use ($limit) {
            return Alert::with(['alertable', 'user'])
                ->active()
                ->latest()
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    public function getUpcomingMaintenance(int $limit = 5): array
    {
        return $this->cacheService->remember("upcoming_maintenance_{$limit}", 5, function () use ($limit) {
            return Maintenance::with(['cctv'])
                ->scheduled()
                ->orderBy('scheduled_at')
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    public function getOfflineCctvs(int $limit = 5): array
    {
        return $this->cacheService->remember("offline_cctvs_{$limit}", 5, function () use ($limit) {
            return Cctv::offline()
                ->with(['building', 'room'])
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    public function getRecentRecordings(int $limit = 5): array
    {
        return $this->cacheService->remember("recent_recordings_{$limit}", 5, function () use ($limit) {
            return Recording::with(['cctv'])
                ->active()
                ->latest()
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    public function getSystemHealth(): array
    {
        return $this->cacheService->remember('system_health', 5, function () {
            // This would typically check actual system health metrics
            // For now, we'll return a mock response
            return [
                'status' => 'healthy',
                'cpu_usage' => rand(10, 80),
                'memory_usage' => rand(20, 70),
                'disk_usage' => rand(10, 90),
                'network_latency' => rand(1, 100),
            ];
        });
    }

    public function clearDashboardCache(): void
    {
        $keys = [
            'cctv_stats',
            'infrastructure_stats',
            'maintenance_stats',
            'alert_stats',
            'system_health',
        ];

        foreach ($keys as $key) {
            $this->cacheService->forget($key);
        }

        // Also clear cached recent items
        for ($i = 1; $i <= 10; $i++) {
            $this->cacheService->forget("recent_alerts_{$i}");
            $this->cacheService->forget("upcoming_maintenance_{$i}");
            $this->cacheService->forget("offline_cctvs_{$i}");
            $this->cacheService->forget("recent_recordings_{$i}");
        }
    }
}
