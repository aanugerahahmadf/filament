<?php

// Simple test to verify map data endpoint
header('Content-Type: application/json');

// Simulate map data response
$data = [
    'buildings' => [
        [
            'id' => 1,
            'name' => 'Main Control Room',
            'latitude' => -6.3700,
            'longitude' => 108.3290,
            'rooms' => [
                [
                    'id' => 101,
                    'building_id' => 1,
                    'name' => 'Electrical Room',
                    'latitude' => -6.3701,
                    'longitude' => 108.3291,
                    'cctvs' => [
                        [
                            'id' => 1001,
                            'building_id' => 1,
                            'room_id' => 101,
                            'name' => 'Electrical Room - Main Camera',
                            'status' => 'online',
                            'latitude' => -6.3701,
                            'longitude' => 108.3291,
                        ],
                    ],
                ],
            ],
            'cctvs' => [
                [
                    'id' => 1002,
                    'building_id' => 1,
                    'name' => 'Main Entrance',
                    'status' => 'offline',
                    'latitude' => -6.3700,
                    'longitude' => 108.3290,
                ],
            ],
        ],
    ],
];

echo json_encode($data, JSON_PRETTY_PRINT);
