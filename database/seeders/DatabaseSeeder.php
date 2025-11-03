<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BuildingSeeder::class,
            RoleSeeder::class,
            RoomSeeder::class,
            CctvSeeder::class,
            RolePermissionSeeder::class,
            SettingsSeeder::class,
            SuperAdminSeeder::class,
            TestUserSeeder::class,
        ]);
    }
}
