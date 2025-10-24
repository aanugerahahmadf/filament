<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateNotificationsUserIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing notifications to populate user_id from notifiable_id
        // where notifiable_type is 'App\Models\User' or similar
        DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->whereNull('user_id')
            ->update(['user_id' => DB::raw('notifiable_id')]);

        // Log how many records were updated
        $updatedCount = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->whereNotNull('user_id')
            ->count();

        Log::info("Updated {$updatedCount} notifications with user_id");

        // For any remaining notifications without user_id, we might want to delete them
        // or handle them differently based on business logic
        $orphanedNotifications = DB::table('notifications')
            ->whereNull('user_id')
            ->count();

        if ($orphanedNotifications > 0) {
            Log::warning("Found {$orphanedNotifications} notifications without user_id");
        }
    }
}
