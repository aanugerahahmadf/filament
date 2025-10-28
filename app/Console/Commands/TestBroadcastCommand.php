<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\TestBroadcastEvent;

class TestBroadcastCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-broadcast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test broadcasting functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $message = $this->ask('What message would you like to broadcast?');

        event(new TestBroadcastEvent($message));

        $this->info('Broadcast event dispatched successfully!');
    }
}
