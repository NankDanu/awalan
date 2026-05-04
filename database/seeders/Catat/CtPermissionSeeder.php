<?php

declare(strict_types=1);

namespace Database\Seeders\Catat;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CtPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Create Catat permissions
        $permissions = [
            // Client permissions
            'catat.clients.view',
            'catat.clients.create',
            'catat.clients.edit',
            'catat.clients.delete',
            // Workspace permissions
            'catat.workspaces.view',
            'catat.workspaces.create',
            'catat.workspaces.edit',
            'catat.workspaces.delete',
            'catat.workspaces.archive',
            // Node permissions
            'catat.nodes.view',
            'catat.nodes.create',
            'catat.nodes.edit',
            'catat.nodes.delete',
            // Link permissions
            'catat.links.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create catat-admin role with all permissions
        $adminRole = Role::firstOrCreate(['name' => 'catat-admin']);
        $adminRole->syncPermissions($permissions);

        // Create catat-viewer role with view-only + link manage
        $viewerPermissions = [
            'catat.clients.view',
            'catat.workspaces.view',
            'catat.nodes.view',
            'catat.links.manage',
        ];
        $viewerRole = Role::firstOrCreate(['name' => 'catat-viewer']);
        $viewerRole->syncPermissions($viewerPermissions);

        // Also assign catat permissions to the existing "admin" role
        // (the main admin role from DatabaseSeeder)
        $mainAdminRole = Role::firstOrCreate(['name' => 'admin']);
        $mainAdminRole->givePermissionTo($permissions);
    }
}
