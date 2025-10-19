<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // System settings
            ['key' => 'app_name', 'value' => 'ATCS KPI'],
            ['key' => 'app_description', 'value' => 'Automated Traffic Control System'],
            ['key' => 'timezone', 'value' => 'UTC'],
            ['key' => 'date_format', 'value' => 'Y-m-d'],
            ['key' => 'time_format', 'value' => 'H:i:s'],
            ['key' => 'locale', 'value' => 'en'],

            // CCTV settings
            ['key' => 'cctv_default_stream_port', 'value' => '554'],
            ['key' => 'cctv_default_fps', 'value' => '30'],
            ['key' => 'cctv_default_resolution', 'value' => '1920x1080'],
            ['key' => 'cctv_stream_check_interval', 'value' => '300'],
            ['key' => 'cctv_offline_alert_threshold', 'value' => '300'],

            // Notification settings
            ['key' => 'notifications_email', 'value' => '1'],
            ['key' => 'notifications_slack', 'value' => '0'],
            ['key' => 'notifications_discord', 'value' => '0'],
            ['key' => 'notifications_alert_severities', 'value' => '["critical","high"]'],

            // Maintenance settings
            ['key' => 'maintenance_preventive_interval', 'value' => '2592000'],
            ['key' => 'maintenance_notification_days', 'value' => '7'],
            ['key' => 'maintenance_contact_email', 'value' => ''],

            // Storage settings
            ['key' => 'storage_recording_path', 'value' => 'recordings'],
            ['key' => 'storage_max_percentage', 'value' => '80'],
            ['key' => 'storage_auto_archive_days', 'value' => '30'],
            ['key' => 'storage_delete_old_days', 'value' => '90'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'updated_at' => now()]
            );
        }
    }
}
