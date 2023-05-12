<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductDispatched;
use App\Models\Route;

class RouteObserver
{
    /**
     * Handle the Route "created" event.
     */
    public function created(Route $route): void
    {
        $products = Product::pluck('id');
        foreach ($products as $product) {
            ProductDispatched::create([
                'product_id' => $product->id,
                'route_id' => $route->id
            ]);
        }

    }

    /**
     * Handle the Route "updated" event.
     */
    public function updated(Route $route): void
    {
        //
    }

    /**
     * Handle the Route "deleted" event.
     */
    public function deleted(Route $route): void
    {
        //
    }

    /**
     * Handle the Route "restored" event.
     */
    public function restored(Route $route): void
    {
        //
    }

    /**
     * Handle the Route "force deleted" event.
     */
    public function forceDeleted(Route $route): void
    {
        //
    }
}
