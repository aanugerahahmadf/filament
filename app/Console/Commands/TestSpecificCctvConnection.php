<?php

namespace App\Console\Commands;

use App\Models\Cctv;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class TestSpecificCctvConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-specific-cctv {ip?} {username?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test connection to a specific IP CCTV camera';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ip = $this->argument('ip') ?? '10.56.236.10';
        $username = $this->argument('username') ?? 'admin';
        $password = $this->argument('password') ?? 'password.123';

        $this->info("Testing connection to CCTV at IP: $ip");
        $this->info("Username: $username");
        $this->info("Password: $password");

        // Create or find the CCTV in the database
        $cctv = Cctv::updateOrCreate(
            ['ip_rtsp' => "rtsp://$ip:554/streaming/channels/101"],
            [
                'building_id' => null,
                'room_id' => null,
                'status' => 'offline',
                'stream_username' => $username,
                'stream_password' => $password,
            ]
        );

        $this->info('CCTV record created/updated with ID: '.$cctv->id);

        // Test 1: Basic connectivity
        $this->info("\n--- Test 1: Basic Connectivity ---");
        $port = 554;
        $socket = @fsockopen($ip, $port, $errno, $errstr, 5);
        if (! $socket) {
            $this->error("Failed to connect to $ip:$port - $errstr ($errno)");

            return 1;
        } else {
            $this->info("Successfully connected to $ip:$port");
            fclose($socket);
        }

        // Test 2: RTSP URL format
        $this->info("\n--- Test 2: RTSP URL Format ---");
        $rtspUrl = $cctv->full_rtsp_url;
        $this->info("Full RTSP URL: $rtspUrl");

        // Test 3: FFmpeg connection test
        $this->info("\n--- Test 3: FFmpeg Connection Test ---");
        $ffmpeg = config('services.ffmpeg.binary', 'ffmpeg');
        $args = [
            $ffmpeg,
            '-rtsp_transport', 'tcp',
            '-timeout', '5000000', // 5 seconds
            '-i', $rtspUrl,
            '-t', '3', // Just 3 seconds test
            '-f', 'null', // Output to null
            '-', // Output file
        ];

        $this->info('Running command: '.implode(' ', $args));

        $process = new Process($args);
        $process->setTimeout(15);

        $process->run(function ($type, $buffer) {
            if ($type === Process::ERR) {
                $this->error('[FFmpeg Error] '.trim($buffer));
            } else {
                $this->line('[FFmpeg Output] '.trim($buffer));
            }
        });

        if ($process->isSuccessful()) {
            $this->info('FFmpeg test successful! Camera is accessible.');

            // Update CCTV status to online
            $cctv->update(['status' => 'online', 'last_seen_at' => now()]);
            $this->info('CCTV status updated to online.');

            return 0;
        } else {
            $this->error('FFmpeg test failed:');
            $this->error('Exit code: '.$process->getExitCode());
            $this->error('Error output: '.$process->getErrorOutput());

            // Update CCTV status to offline
            $cctv->update(['status' => 'offline']);
            $this->info('CCTV status updated to offline.');

            return 1;
        }
    }
}
