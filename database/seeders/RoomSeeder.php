<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all buildings
        $buildings = Building::all();

        if ($buildings->isEmpty()) {
            echo "No buildings found. Please run BuildingSeeder first.\n";

            return;
        }

        // Room names for different building types
        $roomNames = [
            'Main Control Room',
            'Electrical Room',
            'Mechanical Room',
            'Server Room',
            'Storage Area',
            'Office',
            'Conference Room',
            'Lab',
            'Workshop',
            'Security Room',
            'Pump Room',
            'Compressor Room',
            'Tank Farm Control',
            'Fire Station',
            'First Aid Station',
            'Cafeteria',
            'Locker Room',
            'Maintenance Bay',
            'Processing Area',
            'Quality Control',
        ];

        // Create rooms for each building
        foreach ($buildings as $building) {
            // Create 3-5 rooms for each building
            $roomCount = rand(3, 5);

            for ($i = 0; $i < $roomCount; $i++) {
                // Get a random room name
                $roomName = $roomNames[array_rand($roomNames)].' '.chr(65 + $i); // Add A, B, C, etc.

                Room::updateOrCreate(
                    [
                        'building_id' => $building->id,
                        'name' => $roomName,
                    ],
                    [
                        'latitude' => $building->latitude + (rand(-100, 100) / 10000),
                        'longitude' => $building->longitude + (rand(-100, 100) / 10000),
                    ]
                );
            }
        }
    }
}
