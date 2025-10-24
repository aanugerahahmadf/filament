<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pertamina.com'],
            [
                'name' => 'User Interface',
                'username' => 'userinterface',
                'password' => Hash::make('@Admin123'),
            ]
        );
    }
}