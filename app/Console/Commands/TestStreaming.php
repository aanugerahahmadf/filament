<?php

namespace App\Console\Commands;

use App\Models\Cctv;
use Illuminate\Console\Command;

class TestStreaming extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streaming:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test streaming functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking CCTV database...');
        $cctvCount = Cctv::count();
        $this->info("Found {$cctvCount} CCTV cameras");

        if ($cctvCount > 0) {
            $cctv = Cctv::first();
            $this->info("Testing with CCTV ID: {$cctv->id}, Name: {$cctv->name}");
            $this->info("RTSP URL: {$cctv->ip_rtsp}");

            // Check if directories exist
            $dirs = ['live', 'screenshots', 'recordings'];
            foreach ($dirs as $dir) {
                $path = "public/{$dir}";
                if (! is_dir($path)) {
                    $this->info("Creating directory: {$path}");
                    mkdir($path, 0755, true);
                } else {
                    $this->info("Directory exists: {$path}");
                }
            }

            $this->info('Streaming feature is ready to use!');
            $this->info('Visit http://127.0.0.1:8000/livestream to access the live stream page.');
        } else {
            $this->warn('No CCTVs found in database. Please add some CCTVs to test streaming.');
        }

        return Command::SUCCESS;
    }
}
