<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductsClient;

class ClientObserver
{
    /**
     * Handle the Client "created" event.
     */
    public function created(Client $client): void
    {
        if ((auth()->user()->rol_id == '2')) {
            $products = Product::all();
            foreach ($products as $product) {
                ProductsClient::create([
                    'client_id' => $client->id,
                    'product_id' => $product->id,
                    'stock' => 0
                ]);
            }
        }
    }

    /**
     * Handle the Client "updated" event.
     */
    public function updated(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "deleted" event.
     */
    public function deleted(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "restored" event.
     */
    public function restored(Client $client): void
    {
        //
    }

    /**
     * Handle the Client "force deleted" event.
     */
    public function forceDeleted(Client $client): void
    {
        //
    }
}
