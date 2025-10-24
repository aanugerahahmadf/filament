<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-inactive {--minutes=30 : Minutes of inactivity to mark user as offline}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of inactive users to offline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = $this->option('minutes');
        $cutoffTime = Carbon::now()->subMinutes($minutes);

        $inactiveUsers = User::where('status', 'online')
            ->where('last_seen_at', '<', $cutoffTime)
            ->get();

        $count = $inactiveUsers->count();

        if ($count > 0) {
            foreach ($inactiveUsers as $user) {
                $user->update([
                    'status' => 'offline'
                ]);
            }

            $this->info("Updated {$count} inactive users to offline status.");
        } else {
            $this->info("No inactive users found.");
        }

        return 0;
    }
}
