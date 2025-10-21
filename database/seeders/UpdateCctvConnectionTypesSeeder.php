<?php

namespace Database\Seeders;

use App\Models\Cctv;
use Illuminate\Database\Seeder;

class UpdateCctvConnectionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all existing CCTVs to have a default connection type of 'wired'
        // In a real scenario, you might want to determine this based on IP ranges or other criteria
        Cctv::whereNull('connection_type')->update(['connection_type' => 'wired']);
    }
}
