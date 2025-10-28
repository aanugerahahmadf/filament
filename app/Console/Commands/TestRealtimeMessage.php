<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;

class TestRealtimeMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-realtime-message';

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
        $from = User::first();
        $to = User::skip(1)->first() ?? $from;

        if (! $from) {
            $this->error('No users found');
            return 1;
        }

        $body = 'Test realtime message at ' . now()->toDateTimeString();

        $message = Message::create([
            'from_user_id' => $from->id,
            'to_user_id' => $to->id,
            'body' => $body,
            'type' => 'chat',
        ]);

        event(new MessageSent($message));

        $this->info('Message created and broadcast instantly. ID: ' . $message->id);
        return 0;
    }
}
