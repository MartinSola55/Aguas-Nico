<?php

namespace App\Observers;

use App\Models\AbonoLog;
use App\Models\BottleClient;
use App\Models\Cart;
use App\Models\Client;
use App\Models\ProductsClient;
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
        //
    }

    /**
     * Handle the Cart "deleted" event.
     */
    public function deleted(Cart $cart): void
    {
        $client = Client::find($cart->Client->id);
        // Restablecer stock del cliente
        // Abono
        $abonoLog = AbonoLog::where('cart_id', $cart->id)->first();
        if ($abonoLog) {
            if ($abonoLog->AbonoClient->Abono->Product->bottle_type_id != null) {
                $bottleClient = BottleClient::where('client_id', $cart->client_id)->where('bottle_types_id', $abonoLog->AbonoClient->Abono->Product->bottle_type_id)->first();
                $bottleClient->decrement('stock', $abonoLog->quantity);
            } else {
                $productClient = ProductsClient::where('client_id', $cart->client_id)->where('product_id', $abonoLog->AbonoClient->Abono->Product->id)->first();
                $productClient->decrement('stock', $abonoLog->quantity);
            }
            // Volver a incrementar lo disponible del abono
            $abonoLog->AbonoClient->increment('available', $abonoLog->quantity);
        }

        // Productos
        $productsCart = $cart->ProductsCart()->get();
        foreach ($productsCart as $pc) {
            if ($pc->Product->bottle_type_id != null) {
                $bottleClient = BottleClient::where('client_id', $cart->client_id)->where('bottle_types_id', $pc->Product->bottle_type_id)->first();
                $bottleClient->decrement('stock', $pc->quantity);
            } else {
                $productClient = ProductsClient::where('client_id', $cart->client_id)->where('product_id', $pc->product_id)->first();
                $productClient->decrement('stock', $pc->quantity);
            }
            // Deuda del cliente
            $client->debt -= $pc->quantity * $pc->setted_price;
        }

        // Deuda del cliente
        foreach ($cart->CartPaymentMethod()->get() as $cpm) {
            $client->debt += $cpm->amount;
        }
        $abonoClient = $cart->AbonoClient()->first();
        if ($abonoClient) {
            $client->debt -= $abonoClient->setted_price;
            $abonoLog->delete();
            $abonoClient->delete();
        }
        $client->save();

        $cart->ProductsCart()->delete();
        $cart->CartPaymentMethod()->delete();

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
