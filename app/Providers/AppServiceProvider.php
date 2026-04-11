<?php

namespace App\Providers;

use App\Models\CompanySetting;
use App\Models\PersonalAccessToken;
use App\Observers\CompanySettingObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
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
    }
}
