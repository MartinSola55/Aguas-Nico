<?php

namespace App\Providers;

use App\Models\Route;
use Illuminate\Support\ServiceProvider;
use App\Observers\RouteObserver;

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
        Route::observe(RouteObserver::class);
    }
}
