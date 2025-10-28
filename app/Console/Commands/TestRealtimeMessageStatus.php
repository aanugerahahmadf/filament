<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Events\MessageDelivered;

class TestRealtimeMessageStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-realtime-message-status';

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
        $message = Message::latest('id')->first();
        if (! $message) {
            $this->error('No messages found to mark delivered.');
            return 1;
        }

        $message->markAsDelivered();
        event(new MessageDelivered($message));

        $this->info('Message marked delivered and broadcast. ID: ' . $message->id);
        return 0;
    }
}
