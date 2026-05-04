<?php

declare(strict_types=1);

namespace Database\Seeders\Catat;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class CtMenuSeeder extends Seeder
{
    /**
     * Seed the Catat module menus.
     */
    public function run(): void
    {
        // Create Catat main menu group
        $catatMenu = Menu::updateOrCreate(
            ['name' => 'Catat'],
            [
                'parent_id' => null,
                'icon' => 'document-text',
                'sort_order' => 20,
                'is_active' => true,
            ]
        );

        // Add Workspaces menu item
        Menu::updateOrCreate(
            ['route_name' => 'catat.workspaces.index'],
            [
                'name' => 'Workspaces',
                'parent_id' => $catatMenu->id,
                'icon' => 'briefcase',
                'permission_name' => 'catat.workspaces.view',
                'sort_order' => 10,
                'is_active' => true,
            ]
        );

        // Add Clients menu item
        Menu::updateOrCreate(
            ['route_name' => 'catat.clients.index'],
            [
                'name' => 'Clients',
                'parent_id' => $catatMenu->id,
                'icon' => 'users',
                'permission_name' => 'catat.clients.view',
                'sort_order' => 20,
                'is_active' => true,
            ]
        );

        // Add Archive menu item
        Menu::updateOrCreate(
            ['route_name' => 'catat.archive.index'],
            [
                'name' => 'Archive',
                'parent_id' => $catatMenu->id,
                'icon' => 'archive',
                'permission_name' => 'catat.projects.view',
                'sort_order' => 30,
                'is_active' => true,
            ]
        );

        // Add Leads menu item
        Menu::updateOrCreate(
            ['route_name' => 'catat.leads.index'],
            [
                'name' => 'Leads',
                'parent_id' => $catatMenu->id,
                'icon' => 'target',
                'permission_name' => 'catat.projects.view',
                'sort_order' => 25,
                'is_active' => true,
            ]
        );
    }
}
