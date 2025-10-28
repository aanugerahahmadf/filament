<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\SettingsService;

class TestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::first();
        if (! $user) {
            $this->error('No user found');
            return 1;
        }

        $service = new NotificationService(new SettingsService());

        $service->sendUserNotification($user, 'info', 'Sample notification 1', []);
        $service->sendUserNotification($user, 'warning', 'Sample notification 2', ['level' => 'warning']);
        $service->sendUserNotification($user, 'success', 'Sample notification 3', ['level' => 'success']);

        $this->info('Created 3 sample notifications for user: ' . $user->name);
        return 0;
    }
}
