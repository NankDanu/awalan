<?php

namespace App\Providers;

use App\Models\Catat\Client;
use App\Models\Catat\Node;
use App\Models\Catat\Workspace;
use App\Models\CompanySetting;
use App\Observers\Catat\NodeObserver;
use App\Observers\Catat\WorkspaceObserver;
use App\Observers\CompanySettingObserver;
use App\Policies\Catat\ClientPolicy;
use App\Policies\Catat\NodePolicy;
use App\Policies\Catat\WorkspacePolicy;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Client::class => ClientPolicy::class,
        Workspace::class => WorkspacePolicy::class,
        Node::class => NodePolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        // Register observers
        CompanySetting::observe(CompanySettingObserver::class);
        Workspace::observe(WorkspaceObserver::class);
        Node::observe(NodeObserver::class);
    }
}
