<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',
            'view-company-settings',
            'edit-company-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        Permission::query()
            ->where('guard_name', 'web')
            ->whereNotIn('name', $permissions)
            ->delete();

        // Create roles
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        $userRole = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web',
        ]);

        $menuUpsert = function (string $name, ?int $parentId, array $attributes) use ($permissions): void {
            $permissionName = $attributes['permission_name'] ?? null;

            if ($permissionName !== null && ! in_array($permissionName, $permissions, true)) {
                Menu::where('name', $name)
                    ->where('parent_id', $parentId)
                    ->delete();

                return;
            }

            $lookup = ['name' => $name, 'parent_id' => $parentId];

            if (! empty($attributes['route_name'])) {
                $lookup = ['route_name' => $attributes['route_name']];
            } elseif (! empty($attributes['url'])) {
                $lookup = ['url' => $attributes['url'], 'parent_id' => $parentId];
            }

            Menu::updateOrCreate($lookup, [
                'name' => $name,
                'parent_id' => $parentId,
                ...$attributes,
            ]);
        };

        // Assign permissions
        $adminRole->syncPermissions(Permission::all());
        $userRole->syncPermissions(['view-users']);

        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@mail.id',
        ], [
            'name' => 'Administrator',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $admin->syncRoles(['admin']);

        // Create regular user
        $user = User::firstOrCreate([
            'email' => 'user@mail.id',
        ], [
            'name' => 'Regular User',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $user->syncRoles(['user']);

        // Seed menus
        Menu::updateOrCreate([
            'route_name' => 'dashboard',
        ], [
            'name' => 'Dasbor',
            'parent_id' => null,
            'route_name' => 'dashboard',
            'icon' => 'home',
            'sort_order' => 10,
            'is_active' => true,
        ]);


        // Settings Menu
        $settingsMenu = Menu::firstOrCreate([
            'name' => 'Pengaturan',
            'parent_id' => null,
        ], [
            'icon' => 'cog',
            'sort_order' => 30,
            'is_active' => true,
        ]);

        $menuUpsert('Pengguna', $settingsMenu->id, [
            'route_name' => 'users.index',
            'icon' => 'users',
            'permission_name' => 'view-users',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        $menuUpsert('Peran', $settingsMenu->id, [
            'route_name' => 'roles.index',
            'icon' => 'shield',
            'permission_name' => 'view-roles',
            'sort_order' => 20,
            'is_active' => true,
        ]);

        $menuUpsert('Hak Akses', $settingsMenu->id, [
            'route_name' => 'permissions.index',
            'icon' => 'settings',
            'permission_name' => 'view-permissions',
            'sort_order' => 30,
            'is_active' => true,
        ]);

        $menuUpsert('Perusahaan', $settingsMenu->id, [
            'route_name' => 'company-settings.index',
            'icon' => 'building',
            'permission_name' => 'view-company-settings',
            'sort_order' => 40,
            'is_active' => true,
        ]);

        // Seed company settings
        $this->call(CompanySettingSeeder::class);
    }
}
