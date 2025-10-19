<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cctv;
use Symfony\Component\Process\Process;

class TestRtspConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-rtsp-connection {cctv? : The ID of the CCTV to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test RTSP connection for a CCTV camera';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cctvId = $this->argument('cctv');

        if ($cctvId) {
            $cctv = Cctv::find($cctvId);
            if (!$cctv) {
                $this->error("CCTV with ID $cctvId not found");
                return 1;
            }
        } else {
            $cctv = Cctv::first();
            if (!$cctv) {
                $this->error("No CCTV found in database");
                return 1;
            }
        }

        $this->info("Testing RTSP connection for CCTV ID: " . $cctv->id);
        $this->info("RTSP URL: " . $cctv->ip_rtsp);

        // Parse the URL to get host and port
        $parsedUrl = parse_url($cctv->ip_rtsp);
        $host = $parsedUrl['host'] ?? null;
        $port = $parsedUrl['port'] ?? 554;

        if (!$host) {
            $this->error("Invalid RTSP URL format");
            return 1;
        }

        $this->info("Testing connectivity to $host:$port...");

        // Test basic connectivity
        $socket = @fsockopen($host, $port, $errno, $errstr, 5);
        if (!$socket) {
            $this->error("Failed to connect to $host:$port - $errstr ($errno)");
            return 1;
        } else {
            $this->info("Successfully connected to $host:$port");
            fclose($socket);
        }

        $this->info("Testing FFmpeg connection...");

        // Test with FFmpeg
        $ffmpeg = config('services.ffmpeg.binary', 'ffmpeg');
        $args = [
            $ffmpeg,
            '-rtsp_transport', 'tcp',
            '-i', $cctv->ip_rtsp,
            '-t', '5', // Just 5 seconds test
            '-f', 'null', // Output to null
            '-' // Output file
        ];

        $this->info("Running command: " . implode(' ', $args));

        $process = new Process($args);
        $process->setTimeout(15);

        $process->run(function ($type, $buffer) {
            if ($type === Process::ERR) {
                $this->error("[FFmpeg Error] " . trim($buffer));
            } else {
                $this->line("[FFmpeg Output] " . trim($buffer));
            }
        });

        if ($process->isSuccessful()) {
            $this->info("FFmpeg test successful!");
            return 0;
        } else {
            $this->error("FFmpeg test failed:");
            $this->error("Exit code: " . $process->getExitCode());
            $this->error("Error output: " . $process->getErrorOutput());
            return 1;
        }
    }
}
