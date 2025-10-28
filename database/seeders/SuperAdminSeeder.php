<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update super admin user with all required fields
        $user = User::updateOrCreate([
            'email' => 'admin@pertamina.com',
        ],
        [
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'password' => bcrypt('@Admin123'),
            'place_of_birth' => 'Balongan',
            'city' => 'Indramayu',
            'date_of_birth' => '1980-01-01',
            'phone_number' => '+6281234567890',
            'status' => 'online',
            'email_verified_at' => now(),
            'last_seen_at' => now(),
        ]);

        // Assign Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $user->assignRole($superAdminRole);
        }

        $this->command->info('Super admin user created/updated with email: admin@pertamina.com and password: @Admin123');
    }
}
