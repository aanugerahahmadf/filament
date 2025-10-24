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
        // Create super admin user
        $user = User::firstOrCreate([
            'email' => 'admin@pertamina.com',
        ], [
            'name' => 'Super Admin',

            'username' => 'superadmin',
            'password' => bcrypt('@Admin123'),
        ]);

        // Assign Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $user->assignRole($superAdminRole);
        }

        $this->command->info('Super admin user created with email: admin@pertamina.com and password: @Admin123');
    }
}
