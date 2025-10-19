<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NotificationService;
use App\Services\SettingsService;
use Illuminate\Console\Command;

class TestNotification extends Command
{
    protected $signature = 'test:notification';
    protected $description = 'Create a test notification';

    public function handle()
    {
        $user = User::first();
        if (! $user) {
            $this->error('No user found');

            return 1;
        }

        $notificationService = new NotificationService(new SettingsService());
        $notificationService->sendUserNotification($user, 'Test', 'This is a test notification', []);

        $this->info('Test notification created for user: '.$user->name);

        return 0;
    }
}
