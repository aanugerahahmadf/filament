<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    protected string $cacheKey = 'app_settings';

    public function get(string $key, $default = null)
    {
        $settings = $this->getAll();

        return $settings[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        // Update in database
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $value, 'updated_at' => now()]
        );

        // Clear cache
        Cache::forget($this->cacheKey);
    }

    public function getAll(): array
    {
        return Cache::remember($this->cacheKey, 3600, function () {
            return DB::table('settings')->pluck('value', 'key')->toArray();
        });
    }

    public function getSystemSettings(): array
    {
        return [
            'app_name' => $this->get('app_name', 'ATCS KPI'),
            'app_description' => $this->get('app_description', 'Automated Traffic Control System'),
            'timezone' => $this->get('timezone', 'UTC'),
            'date_format' => $this->get('date_format', 'Y-m-d'),
            'time_format' => $this->get('time_format', 'H:i:s'),
            'locale' => $this->get('locale', 'en'),
        ];
    }

    public function getCctvSettings(): array
    {
        return [
            'default_stream_port' => $this->get('cctv_default_stream_port', 554),
            'default_fps' => $this->get('cctv_default_fps', 30),
            'default_resolution' => $this->get('cctv_default_resolution', '1920x1080'),
            'stream_check_interval' => $this->get('cctv_stream_check_interval', 300), // 5 minutes
            'offline_alert_threshold' => $this->get('cctv_offline_alert_threshold', 300), // 5 minutes
        ];
    }

    public function getNotificationSettings(): array
    {
        return [
            'email_notifications' => $this->get('notifications_email', true),
            'slack_notifications' => $this->get('notifications_slack', false),
            'discord_notifications' => $this->get('notifications_discord', false),
            'alert_severities' => $this->get('notifications_alert_severities', ['critical', 'high']),
        ];
    }

    public function getMaintenanceSettings(): array
    {
        return [
            'preventive_maintenance_interval' => $this->get('maintenance_preventive_interval', 2592000), // 30 days
            'maintenance_notification_days' => $this->get('maintenance_notification_days', 7), // 7 days
            'maintenance_contact_email' => $this->get('maintenance_contact_email', ''),
        ];
    }

    public function getStorageSettings(): array
    {
        return [
            'recording_storage_path' => $this->get('storage_recording_path', 'recordings'),
            'max_storage_percentage' => $this->get('storage_max_percentage', 80),
            'auto_archive_days' => $this->get('storage_auto_archive_days', 30),
            'delete_old_recordings_days' => $this->get('storage_delete_old_days', 90),
        ];
    }

    public function updateSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function getSettingsByGroup(string $group): array
    {
        $allSettings = $this->getAll();
        $groupSettings = [];

        foreach ($allSettings as $key => $value) {
            if (str_starts_with($key, $group.'_')) {
                $groupSettings[$key] = $value;
            }
        }

        return $groupSettings;
    }
}
