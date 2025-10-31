<?php

namespace App\Console\Commands;

use App\Events\NotificationCreated;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendTestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:test {userId?} {--type=info} {--message=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test notification to a user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('userId');

        if (!$userId) {
            $user = User::first();
            if (!$user) {
                $this->error('No users found in the database.');
                return 1;
            }
            $userId = $user->id;
        } else {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }
        }

        $type = $this->option('type');
        $message = $this->option('message') ?? 'This is a test notification sent at ' . now()->format('Y-m-d H:i:s');

        $notification = Notification::create([
            'id' => Str::uuid(),
            'user_id' => $userId,
            'type' => $type,
            'data' => [
                'message' => $message,
                'source' => 'Test Command',
                'timestamp' => now()->toISOString()
            ],
            'read_at' => null,
        ]);

        // Dispatch the event manually to ensure it's broadcast
        event(new NotificationCreated($notification));

        $this->info("Test notification sent to user {$userId} ({$user->name})");
        $this->info("Notification ID: {$notification->id}");
        $this->info("Type: {$type}");
        $this->info("Message: {$message}");

        return 0;
    }
}
