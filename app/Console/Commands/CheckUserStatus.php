<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:status {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the status of a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        $this->info("User: {$user->name}");
        $this->info("Status: {$user->status}");
        $this->info("Last seen: {$user->last_seen_at}");

        return 0;
    }
}
