<?php

declare(strict_types=1);

namespace Nank\Awalan\Console\Commands;

use Illuminate\Console\Command;
use Nank\Awalan\Menu\MenuManager;

class MenuInstallCommand extends Command
{
    protected $signature = 'menu:install
                            {--guard=web : Guard name for the permissions}
                            {--force : Re-create permissions even if they already exist}';

    protected $description = 'Create Spatie permissions for all menus registered by installed packages';

    public function handle(): int
    {
        if (! class_exists(\Spatie\Permission\Models\Permission::class)) {
            $this->error('spatie/laravel-permission is not installed.');

            return self::FAILURE;
        }

        $guard = $this->option('guard');
        $permissions = MenuManager::permissions();

        if (empty($permissions)) {
            $this->info('No menu permissions registered.');

            return self::SUCCESS;
        }

        $created = 0;
        $skipped = 0;

        foreach ($permissions as $name) {
            $exists = \Spatie\Permission\Models\Permission::where('name', $name)
                ->where('guard_name', $guard)
                ->exists();

            if ($exists && ! $this->option('force')) {
                $this->line("  <fg=yellow>SKIP</> {$name}");
                $skipped++;
                continue;
            }

            \Spatie\Permission\Models\Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => $guard,
            ]);

            $this->line("  <fg=green>CREATE</> {$name}");
            $created++;
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->newLine();
        $this->info("Done. Created: {$created}, Skipped: {$skipped}.");

        return self::SUCCESS;
    }
}
