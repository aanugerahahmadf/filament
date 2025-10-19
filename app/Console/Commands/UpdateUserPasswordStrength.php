<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserPasswordStrength extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-password-strength';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update password strength flags for all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating password strength flags for all users...');

        $users = User::all();
        $updatedCount = 0;

        foreach ($users as $user) {
            // We can't actually check the password strength of existing passwords
            // since they are hashed. We'll assume all existing passwords are weak
            // and let users update them when they log in.
            if (!$user->has_strong_password) {
                $user->update(['has_strong_password' => false]);
                $updatedCount++;
            }
        }

        $this->info("Updated {$updatedCount} users.");
        $this->info('Password strength flags updated successfully!');
    }
}
