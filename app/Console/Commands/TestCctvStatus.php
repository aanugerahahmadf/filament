<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\CctvStatusChanged;

class TestCctvStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-cctv-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test CCTV status change broadcasting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing CCTV status change broadcasting...');

        // Simulate a CCTV status change
        $cctvId = rand(1, 100);
        $cctvNames = ['Main Entrance', 'Control Room', 'Tank Area', 'Loading Dock', 'Perimeter'];
        $statuses = ['online', 'offline', 'recording', 'error'];
        $locations = ['Building A', 'Building B', 'Storage Area', 'Administration', 'Perimeter'];

        $cctvName = $cctvNames[array_rand($cctvNames)];
        $status = $statuses[array_rand($statuses)];
        $location = $locations[array_rand($locations)];

        event(new CctvStatusChanged($cctvId, $cctvName, $status, $location));

        $this->info("Broadcasted CCTV status change:");
        $this->line("ID: $cctvId");
        $this->line("Name: $cctvName");
        $this->line("Status: $status");
        $this->line("Location: $location");
    }
}
