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
        // No buildings or rooms will be created as per user request
        // We'll only create the specific CCTV with provided credentials if buildings exist
        $buildings = Building::all();

        if ($buildings->isEmpty()) {
            echo "No buildings found. Please run BuildingSeeder first.\n";

            return;
        }

        // Create the specific CCTV with provided credentials
        // This will only work if buildings already exist in the database
        if ($buildings->isNotEmpty()) {
            $building = $buildings->first();

            // Try to find an existing room for this building
            $room = Room::where('building_id', $building->id)->first();

            // Prepare CCTV data
            $cctvData = [
                'building_id' => $building->id,
                'status' => 'online',
                'stream_username' => 'admin',
                'stream_password' => 'password.123',
                'hls_path' => null,
            ];

            // Add room_id only if a room exists
            if ($room) {
                $cctvData['room_id'] = $room->id;
                $cctvData['latitude'] = $room->latitude;
                $cctvData['longitude'] = $room->longitude;
            } else {
                $cctvData['latitude'] = $building->latitude;
                $cctvData['longitude'] = $building->longitude;
            }

            // Create the specific CCTV with provided credentials
            Cctv::updateOrCreate(
                [
                    'ip_rtsp' => 'rtsp://10.56.236.10:554/streaming/channels/101',
                ],
                $cctvData
            );
        }
    }
}
