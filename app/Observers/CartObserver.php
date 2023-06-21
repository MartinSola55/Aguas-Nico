<?php

namespace App\Observers;

use App\Models\Cart;
use App\Models\Client;

class CartObserver
{
    /**
     * Handle the Cart "created" event.
     */
    public function created(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "updated" event.
     */
    public function updated(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "deleted" event.
     */
    public function deleted(Cart $cart): void
    {
        $cart->ProductsCart()->delete();
        $cart->CartPaymentMethod()->delete();

        $client = Client::find($cart->Client->id);
        $client->debt -= $cart->take_debt;
        $client->save();
    }

    /**
     * Handle the Cart "restored" event.
     */
    public function restored(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "force deleted" event.
     */
    public function forceDeleted(Cart $cart): void
    {
        //
    }
}
