<?php

// Simple script to test map data structure
require_once 'vendor/autoload.php';

// Create a mock building structure
$buildings = [
    [
        'id' => 1,
        'name' => 'Main Control Room',
        'latitude' => -6.3700,
        'longitude' => 108.3290,
        'marker_icon_path' => '/images/building-icon.png',
        'rooms' => [
            [
                'id' => 101,
                'building_id' => 1,
                'name' => 'Electrical Room A',
                'latitude' => -6.3701,
                'longitude' => 108.3291,
                'marker_icon_path' => '/images/room-icon.png',
                'cctvs' => [
                    [
                        'id' => 1001,
                        'building_id' => 1,
                        'room_id' => 101,
                        'name' => 'Electrical Room A - Main Entrance',
                        'ip_rtsp' => 'rtsp://admin:password@10.56.236.10/streaming/channels/1',
                        'status' => 'online',
                        'latitude' => -6.3701,
                        'longitude' => 108.3291,
                        'hls_path' => '/streams/1001.m3u8',
                        'last_seen_at' => '2025-10-10 10:00:00',
                    ],
                    [
                        'id' => 1002,
                        'building_id' => 1,
                        'room_id' => 101,
                        'name' => 'Electrical Room A - Equipment Bay',
                        'ip_rtsp' => 'rtsp://admin:password@10.56.236.11/streaming/channels/1',
                        'status' => 'offline',
                        'latitude' => -6.3701,
                        'longitude' => 108.3292,
                        'hls_path' => null,
                        'last_seen_at' => '2025-10-09 15:30:00',
                    ],
                ],
            ],
            [
                'id' => 102,
                'building_id' => 1,
                'name' => 'Server Room B',
                'latitude' => -6.3699,
                'longitude' => 108.3289,
                'marker_icon_path' => '/images/room-icon.png',
                'cctvs' => [
                    [
                        'id' => 1003,
                        'building_id' => 1,
                        'room_id' => 102,
                        'name' => 'Server Room B - Main Entrance',
                        'ip_rtsp' => 'rtsp://admin:password@10.56.236.12/streaming/channels/1',
                        'status' => 'maintenance',
                        'latitude' => -6.3699,
                        'longitude' => 108.3289,
                        'hls_path' => null,
                        'last_seen_at' => '2025-10-10 09:45:00',
                    ],
                ],
            ],
        ],
        'cctvs' => [
            [
                'id' => 1004,
                'building_id' => 1,
                'name' => 'Main Control Room - Lobby',
                'ip_rtsp' => 'rtsp://admin:password@10.56.236.13/streaming/channels/1',
                'status' => 'online',
                'latitude' => -6.3700,
                'longitude' => 108.3290,
                'hls_path' => '/streams/1004.m3u8',
                'last_seen_at' => '2025-10-10 10:15:00',
            ],
        ],
    ],
];

// Output as JSON
header('Content-Type: application/json');
echo json_encode(['buildings' => $buildings], JSON_PRETTY_PRINT);
