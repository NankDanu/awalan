<?php

declare(strict_types=1);

namespace Nank\Awalan\Providers;

use Illuminate\Support\ServiceProvider;
use Nank\Awalan\Console\Commands\MenuInstallCommand;
use Nank\Awalan\Menu\MenuManager;

class BaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/base.php', 'base');

        $this->app->singleton(MenuManager::class, fn () => new MenuManager());
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'base');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        MenuManager::add([
            'label' => 'Dashboard',
            'icon' => 'heroicon-o-home',
            'route' => 'dashboard',
            'permission' => null,
            'order' => 10,
            'active' => ['dashboard'],
            'children' => [],
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                MenuInstallCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../../config/base.php' => config_path('base.php'),
            ], 'base-config');

            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/base'),
            ], 'base-views');
        }
    }
}
