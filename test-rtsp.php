<?php

// Test script to check RTSP connectivity
require_once 'vendor/autoload.php';

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

// Get a sample CCTV from the database
$cctv = \App\Models\Cctv::first();
if (!$cctv) {
    echo "No CCTV found in database\n";
    exit(1);
}

echo "Testing RTSP connection for CCTV ID: " . $cctv->id . "\n";
echo "RTSP URL: " . $cctv->ip_rtsp . "\n";

// Test basic connectivity first
$rtspUrl = $cctv->ip_rtsp;
$parsedUrl = parse_url($rtspUrl);
$host = $parsedUrl['host'] ?? null;
$port = $parsedUrl['port'] ?? 554;

if (!$host) {
    echo "Invalid RTSP URL format\n";
    exit(1);
}

echo "Testing connectivity to $host:$port...\n";

// Simple socket test
$socket = @fsockopen($host, $port, $errno, $errstr, 5);
if (!$socket) {
    echo "Failed to connect to $host:$port - $errstr ($errno)\n";
    exit(1);
} else {
    echo "Successfully connected to $host:$port\n";
    fclose($socket);
}

echo "Testing FFmpeg connection...\n";

// Test with FFmpeg
$ffmpeg = 'ffmpeg';
$args = [
    $ffmpeg,
    '-rtsp_transport', 'tcp',
    '-i', $cctv->ip_rtsp,
    '-t', '5', // Just 5 seconds test
    '-f', 'null', // Output to null
    '-' // Output file
];

$process = new Process($args);
$process->setTimeout(10);

echo "Running command: " . implode(' ', $args) . "\n";

$process->run(function ($type, $buffer) {
    if ($type === Process::ERR) {
        echo "[ERROR] $buffer";
    } else {
        echo "[OUTPUT] $buffer";
    }
});

if ($process->isSuccessful()) {
    echo "FFmpeg test successful!\n";
} else {
    echo "FFmpeg test failed:\n";
    echo "Exit code: " . $process->getExitCode() . "\n";
    echo "Error output: " . $process->getErrorOutput() . "\n";
}
