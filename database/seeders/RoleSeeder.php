<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $uiRole = Role::firstOrCreate(['name' => 'User Interface', 'guard_name' => 'web']);

        $user = User::first();
        if ($user && ! $user->hasRole($superAdmin)) {
            $user->assignRole($superAdmin);
        }
    }
}
