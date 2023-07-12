<?php

namespace App\Observers;

use App\Models\Cart;
use App\Models\Client;
use App\Models\DebtPaymentLog;
use Carbon\Carbon;

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
        if ($cart->is_static == false && $cart->state == 1) {
            $debtPaymentLog = DebtPaymentLog::firstOrCreate(
                [
                    'cart_id' => $cart->id,
                    'client_id' => $cart->client_id
                ],
                [
                    'client_id' => $cart->client_id,
                    'cart_id' => $cart->id,
                    'created_at' => Carbon::now(),
                ]
            );
            
            $debtPaymentLog->debt = $cart->ProductsCart()->get()->sum(function ($item) {
                return $item->quantity * $item->setted_price;
            });
            $debtPaymentLog->debt -= $cart->CartPaymentMethod()->sum('amount');
            $debtPaymentLog->updated_at = Carbon::now();
            $debtPaymentLog->save();
        }
    }

    /**
     * Handle the Cart "deleted" event.
     */
    public function deleted(Cart $cart): void
    {
        $cart->ProductsCart()->delete();
        $cart->CartPaymentMethod()->delete();
        DebtPaymentLog::where('cart_id', $cart->id)->delete();

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
