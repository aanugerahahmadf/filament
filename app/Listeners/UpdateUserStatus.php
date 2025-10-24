<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateUserStatus
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;

        if ($user instanceof User) {
            if ($event instanceof Login) {
                $user->update([
                    'status' => 'online',
                    'last_seen_at' => now(),
                ]);
            } elseif ($event instanceof Logout) {
                $user->update([
                    'status' => 'offline',
                    'last_seen_at' => now(),
                ]);
            }
        }
    }
}
