<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheService
{
    protected string $prefix = 'atcs_kpi_';

    public function remember(string $key, int $minutes, callable $callback)
    {
        return Cache::remember($this->prefix.$key, $minutes, $callback);
    }

    public function get(string $key, $default = null)
    {
        return Cache::get($this->prefix.$key, $default);
    }

    public function put(string $key, $value, int $minutes = 60): void
    {
        Cache::put($this->prefix.$key, $value, $minutes);
    }

    public function forget(string $key): void
    {
        Cache::forget($this->prefix.$key);
    }

    public function flush(): void
    {
        Cache::flush();
    }

    public function has(string $key): bool
    {
        return Cache::has($this->prefix.$key);
    }

    public function increment(string $key, int $value = 1)
    {
        return Cache::increment($this->prefix.$key, $value);
    }

    public function decrement(string $key, int $value = 1)
    {
        return Cache::decrement($this->prefix.$key, $value);
    }

    public function getCctvStatus(int $cctvId)
    {
        return $this->get("cctv_{$cctvId}_status");
    }

    public function setCctvStatus(int $cctvId, string $status, int $minutes = 5): void
    {
        $this->put("cctv_{$cctvId}_status", $status, $minutes);
    }

    public function getDashboardStats()
    {
        return $this->get('dashboard_stats');
    }

    public function setDashboardStats(array $stats, int $minutes = 10): void
    {
        $this->put('dashboard_stats', $stats, $minutes);
    }

    public function getMapData()
    {
        return $this->get('map_data');
    }

    public function setMapData(array $data, int $minutes = 5): void
    {
        $this->put('map_data', $data, $minutes);
    }

    public function getAlertsCount(int $userId)
    {
        return $this->get("user_{$userId}_alerts_count", 0);
    }

    public function setAlertsCount(int $userId, int $count, int $minutes = 1): void
    {
        $this->put("user_{$userId}_alerts_count", $count, $minutes);
    }

    public function getMaintenanceStats()
    {
        return $this->get('maintenance_stats');
    }

    public function setMaintenanceStats(array $stats, int $minutes = 10): void
    {
        $this->put('maintenance_stats', $stats, $minutes);
    }

    public function clearAllCctvCache(): void
    {
        // Clear all CCTV-related cache
        $keys = Redis::keys($this->prefix.'cctv_*');
        if (! empty($keys)) {
            Redis::del($keys);
        }
    }

    public function clearUserCache(int $userId): void
    {
        // Clear user-specific cache
        $keys = Redis::keys($this->prefix."user_{$userId}_*");
        if (! empty($keys)) {
            Redis::del($keys);
        }
    }
}
