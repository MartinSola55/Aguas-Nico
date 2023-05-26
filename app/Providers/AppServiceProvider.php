<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Route;
use App\Observers\CartObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
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
        Cart::observe(CartObserver::class);

        if($this->app->environment('production'))
        {
            URL::forceScheme('https');
        }
    }
}
