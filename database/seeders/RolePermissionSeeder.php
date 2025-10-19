<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // CCTV permissions
            'view cctvs',
            'create cctvs',
            'edit cctvs',
            'delete cctvs',
            'start cctv stream',
            'stop cctv stream',
            'check cctv status',

            // Building permissions
            'view buildings',
            'create buildings',
            'edit buildings',
            'delete buildings',

            // Room permissions
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',

            // Maintenance permissions
            'view maintenances',
            'create maintenances',
            'edit maintenances',
            'delete maintenances',
            'start maintenances',
            'complete maintenances',
            'cancel maintenances',

            // Alert permissions
            'view alerts',
            'acknowledge alerts',
            'resolve alerts',
            'suppress alerts',

            // Recording permissions
            'view recordings',
            'download recordings',
            'archive recordings',
            'restore recordings',
            'delete recordings',

            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Contact permissions
            'view contacts',
            'create contacts',
            'edit contacts',
            'delete contacts',

            // Report permissions
            'view reports',
            'export reports',

            // Settings permissions
            'view settings',
            'edit settings',

            // Admin permissions
            'access admin panel',
            'manage roles',
            'manage permissions',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $technicianRole = Role::firstOrCreate(['name' => 'technician']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);

        // Assign permissions to roles
        // Admin role gets all permissions
        $adminRole->givePermissionTo(Permission::all());

        // Technician role permissions
        $technicianPermissions = [
            'view cctvs',
            'start cctv stream',
            'stop cctv stream',
            'check cctv status',
            'view maintenances',
            'edit maintenances',
            'start maintenances',
            'complete maintenances',
            'cancel maintenances',
            'view alerts',
            'acknowledge alerts',
            'resolve alerts',
            'view recordings',
            'download recordings',
            'view reports',
        ];

        $technicianRole->givePermissionTo($technicianPermissions);

        // Operator role permissions
        $operatorPermissions = [
            'view cctvs',
            'start cctv stream',
            'stop cctv stream',
            'check cctv status',
            'view maintenances',
            'view alerts',
            'acknowledge alerts',
            'view recordings',
            'download recordings',
            'view reports',
        ];

        $operatorRole->givePermissionTo($operatorPermissions);

        // Viewer role permissions
        $viewerPermissions = [
            'view cctvs',
            'view maintenances',
            'view alerts',
            'view recordings',
            'view reports',
        ];

        $viewerRole->givePermissionTo($viewerPermissions);
    }
}
