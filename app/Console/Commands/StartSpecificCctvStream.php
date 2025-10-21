<?php

namespace App\Console\Commands;

use App\Models\Cctv;
use App\Services\FfmpegStreamService;
use Illuminate\Console\Command;

class StartSpecificCctvStream extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-specific-cctv {ip?} {username?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start streaming from a specific IP CCTV camera';

    /**
     * Execute the console command.
     */
    public function handle(FfmpegStreamService $ffmpegService)
    {
        $ip = $this->argument('ip') ?? '10.56.236.10';
        $username = $this->argument('username') ?? 'admin';
        $password = $this->argument('password') ?? 'password.123';

        $this->info("Starting stream for CCTV at IP: $ip");

        // Find or create the CCTV in the database
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

        $this->info('CCTV record ID: '.$cctv->id);

        try {
            $this->info('Attempting to start stream...');
            $hlsUrl = $ffmpegService->startStream($cctv);

            if (strpos($hlsUrl, 'Failed') === 0) {
                $this->error("Stream failed to start: $hlsUrl");

                return 1;
            }

            $this->info('Stream started successfully!');
            $this->info("HLS URL: $hlsUrl");

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to start stream: '.$e->getMessage());

            return 1;
        }
    }
}
