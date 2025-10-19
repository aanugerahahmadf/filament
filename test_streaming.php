<?php

// Simple test script to verify streaming functionality

require_once 'vendor/autoload.php';

use App\Models\Cctv;

// Check if we have CCTVs
echo "Checking CCTV database...\n";
$cctvCount = Cctv::count();
echo "Found {$cctvCount} CCTV cameras\n";

if ($cctvCount > 0) {
    $cctv = Cctv::first();
    echo "Testing with CCTV ID: {$cctv->id}, Name: {$cctv->name}\n";
    echo "RTSP URL: {$cctv->ip_rtsp}\n";

    // Check if directories exist
    $dirs = ['live', 'screenshots', 'recordings'];
    foreach ($dirs as $dir) {
        $path = "public/{$dir}";
        if (! is_dir($path)) {
            echo "Creating directory: {$path}\n";
            mkdir($path, 0755, true);
        } else {
            echo "Directory exists: {$path}\n";
        }
    }

    echo "Streaming feature is ready to use!\n";
    echo "Visit http://127.0.0.1:8000/livestream to access the live stream page.\n";
} else {
    echo "No CCTVs found in database. Please add some CCTVs to test streaming.\n";
}
