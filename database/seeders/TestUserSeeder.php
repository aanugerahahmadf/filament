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
            ['email' => 'test@pertamina.com'], // Changed email to avoid conflict
            [
                'name' => 'Test User',
                'username' => 'testuser',
                'password' => Hash::make('@Pasword123'),
            ]
        );
    }
}
