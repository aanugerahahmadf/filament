<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddHasStrongPasswordColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:add-has-strong-password-column';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add has_strong_password column to users table if it does not exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking if has_strong_password column exists...');

        // Check if the column exists
        try {
            if (!Schema::hasColumn('users', 'has_strong_password')) {
                $this->info('Column does not exist. Adding has_strong_password column to users table...');

                // Add the column
                DB::statement('ALTER TABLE users ADD COLUMN has_strong_password BOOLEAN DEFAULT FALSE');

                $this->info('Column has_strong_password added successfully!');
            } else {
                $this->info('Column has_strong_password already exists.');
            }
        } catch (\Exception $e) {
            $this->error('Error adding column: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
