<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            Notification::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'type' => 'System Update',
                'data' => [
                    'message' => 'New system features have been deployed successfully.',
                    'details' => 'Version 2.1.0 includes performance improvements and bug fixes.'
                ],
                'read_at' => null,
            ]);

            Notification::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'type' => 'Security Alert',
                'data' => [
                    'message' => 'Unusual login activity detected on your account.',
                    'details' => 'Login attempt from unknown device at 02:45 AM. No action required if this was you.'
                ],
                'read_at' => now(),
            ]);

            Notification::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'type' => 'Maintenance Notice',
                'data' => [
                    'message' => 'Scheduled maintenance will occur this weekend.',
                    'details' => 'System will be offline from Saturday 10:00 PM to Sunday 2:00 AM for routine maintenance.'
                ],
                'read_at' => null,
            ]);
        }
    }
}
