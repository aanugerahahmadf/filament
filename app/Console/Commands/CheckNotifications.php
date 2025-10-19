<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckNotifications extends Command
{
    protected $signature = 'check:notifications';
    protected $description = 'Check notification count for the first user';

    public function handle()
    {
        $user = User::first();
        if (! $user) {
            $this->error('No user found');

            return 1;
        }

        $totalNotifications = $user->notifications()->count();
        $unreadNotifications = $user->notifications()->unread()->count();

        $this->info("User: {$user->name}");
        $this->info("Total notifications: {$totalNotifications}");
        $this->info("Unread notifications: {$unreadNotifications}");

        return 0;
    }
}
