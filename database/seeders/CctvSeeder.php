<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Cctv;
use App\Models\Room;
use Illuminate\Database\Seeder;

class CctvSeeder extends Seeder
{
    public function run(): void
    {
        // Get all buildings
        $buildings = Building::all();

        if ($buildings->isEmpty()) {
            echo "No buildings found. Please run BuildingSeeder first.\n";

            return;
        }

        // Status options for CCTVs
        $statuses = ['online', 'offline', 'maintenance'];

        // Camera names
        $cameraNames = [
            'Main Entrance',
            'Secondary Entrance',
            'Parking Area',
            'Loading Dock',
            'Control Panel',
            'Equipment Bay',
            'Storage Area',
            'Work Zone',
            'Emergency Exit',
            'Stairwell',
            'Elevator',
            'Hallway',
            'Corridor',
            'Reception',
            'Lobby',
        ];

        // Create CCTVs for each building and room
        foreach ($buildings as $building) {
            // Get or create rooms for this building
            $rooms = Room::where('building_id', $building->id)->get();

            if ($rooms->isEmpty()) {
                // Create at least one room if none exist
                $room = Room::create([
                    'building_id' => $building->id,
                    'name' => 'Main Room A',
                    'latitude' => $building->latitude,
                    'longitude' => $building->longitude,
                ]);
                $rooms = collect([$room]);
            }

            // Create 2-4 CCTVs for each room
            foreach ($rooms as $room) {
                $cctvCount = rand(2, 4);

                for ($i = 1; $i <= $cctvCount; $i++) {
                    // Randomly assign status
                    $status = $statuses[array_rand($statuses)];

                    // Get a random camera name
                    $cameraName = $cameraNames[array_rand($cameraNames)].' '.$i;

                    Cctv::updateOrCreate(
                        [
                            'building_id' => $building->id,
                            'room_id' => $room->id,
                            'name' => $room->name.' - '.$cameraName,
                        ],
                        [
                            'ip_rtsp' => 'rtsp://admin:password.123@10.56.236.'.rand(10, 250).'/streaming/channels/',
                            'status' => $status,
                            'latitude' => $room->latitude + (rand(-50, 50) / 100000),
                            'longitude' => $room->longitude + (rand(-50, 50) / 100000),
                            'hls_path' => null,
                        ]
                    );
                }
            }

            // Create some building-level CCTVs (not assigned to specific rooms)
            $buildingCctvCount = rand(1, 3);
            for ($i = 1; $i <= $buildingCctvCount; $i++) {
                $status = $statuses[array_rand($statuses)];
                $cameraName = $cameraNames[array_rand($cameraNames)].' '.$i;

                Cctv::updateOrCreate(
                    [
                        'building_id' => $building->id,
                        'name' => $building->name.' - '.$cameraName,
                    ],
                    [
                        'ip_rtsp' => 'rtsp://admin:password.123@10.56.236.'.rand(10, 250).'/streaming/channels/',
                        'status' => $status,
                        'latitude' => $building->latitude + (rand(-50, 50) / 100000),
                        'longitude' => $building->longitude + (rand(-50, 50) / 100000),
                        'hls_path' => null,
                    ]
                );
            }
        }
    }
}
