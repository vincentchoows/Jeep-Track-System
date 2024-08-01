<?php

namespace App\Providers;

use OpenAdmin\Admin\Config\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

use App\Models\PermitApplication;
use App\Models\Vehicle;
use App\Observers\PermitApplicationObserver;
use App\Observers\VehicleObserver;

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
        //Add template path here
        Config::load();  // Add this
        Blade::anonymousComponentPath(__DIR__.'/../components/login');
        Blade::anonymousComponentNamespace('components.layouts','appLayout');

        //cPanel public path
        // if (env('APP_ENV') !== 'local') {
        //     app()->usePublicPath(env('APP_URL'));
        // }

    }
}
