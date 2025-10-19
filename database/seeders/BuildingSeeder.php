<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = [
            ['name' => 'Gedung Kolaboratif', 'lat' => -6.3698000, 'lng' => 108.3289000],
            ['name' => 'Gerbang Utama', 'lat' => -6.3689000, 'lng' => 108.3301000],
            ['name' => 'AWI', 'lat' => -6.3703000, 'lng' => 108.3312000],
            ['name' => 'Shelter Maintenance Area 1', 'lat' => -6.3710000, 'lng' => 108.3295000],
            ['name' => 'Shelter Maintenance Area 2', 'lat' => -6.3714000, 'lng' => 108.3303000],
            ['name' => 'Shelter Maintenance Area 3', 'lat' => -6.3720000, 'lng' => 108.3309000],
            ['name' => 'Shelter Maintenance Area 4', 'lat' => -6.3726000, 'lng' => 108.3315000],
            ['name' => 'Shelter White OM', 'lat' => -6.3684000, 'lng' => 108.3278000],
            ['name' => 'Pintu Masuk Area Kilang', 'lat' => -6.3682000, 'lng' => 108.3292000],
            ['name' => 'Marine Region III', 'lat' => -6.3669000, 'lng' => 108.3332000],
            ['name' => 'Main Control Room', 'lat' => -6.3693000, 'lng' => 108.3324000],
            ['name' => 'Tank Farm Area 1', 'lat' => -6.3733000, 'lng' => 108.3338000],
            ['name' => 'Gedung EXOR', 'lat' => -6.3709000, 'lng' => 108.3341000],
            ['name' => 'Produksi CDU', 'lat' => -6.3718000, 'lng' => 108.3352000],
            ['name' => 'HSSE Demo Room', 'lat' => -6.3679000, 'lng' => 108.3286000],
            ['name' => 'Gedung Amanah', 'lat' => -6.3696000, 'lng' => 108.3276000],
            ['name' => 'POC', 'lat' => -6.3687000, 'lng' => 108.3269000],
            ['name' => 'JGC', 'lat' => -6.3724000, 'lng' => 108.3327000],
        ];

        foreach ($buildings as $b) {
            Building::updateOrCreate(
                ['name' => $b['name']],
                [
                    'latitude' => $b['lat'],
                    'longitude' => $b['lng'],
                    'marker_icon_path' => '/images/logo-pertamina.png',
                ]
            );
        }
    }
}
