<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NoAvatarUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user without an avatar
        User::factory()->create([
            'name' => 'No Avatar User',
            'email' => 'noavatar@example.com',
            'password' => Hash::make('password'),
            'avatar' => null
        ]);
    }
}
