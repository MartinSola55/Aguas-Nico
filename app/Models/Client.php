<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'adress',
        'phone',
        'email',
        'debt',
        'dni',
        'invoice',
        'is_active',
        'observation',
        'user_id',
        'invoice_type',
        'business_name',
        'tax_condition',
        'cuit',
        'tax_address',
        'abono_id',
    ];

    public function Products()
    {
        return $this->hasManyThrough(Product::class, ProductsClient::class, 'client_id', 'id', 'id', 'product_id');
    }

    public function ProductsClient()
    {
        return $this->hasMany(ProductsClient::class, 'client_id');
    }

    public function Carts()
    {
        return $this->hasMany(Cart::class, 'client_id');
    }

    public function BottleClient()
    {
        return $this->hasMany(BottleClient::class, 'client_id');
    }

    public function getDebtOfTheMonth()
    {
        // Ahora solo calcula lo que consumio, no la deuda total
        $total = 0;
        $carts = Cart::where('client_id', $this->id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->get();
        foreach ($carts as $cart) {
            foreach ($cart->ProductsCart as $product) {
                $total += $product->quantity * $product->setted_price;
            }
            // foreach ($cart->CartPaymentMethod as $pm) {
            //     $total -= $pm->amount;
            // }
        }
        // $transfers = Transfer::where('client_id', $this->id)->whereMonth('received_from', date('m'))->whereYear('received_from', date('Y'))->get();
        // foreach ($transfers as $transfer) {
        //     $total -= $transfer->amount;
        // }
        $abono = AbonoClient::where('client_id', $this->id)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->first();
        if ($abono) {
            $total += $abono->setted_price;
        }
        return $total;
    }

    public function getDebtOfPreviousMonth()
    {
        // Ahora solo calcula lo que consumio, no la deuda total
        $total = 0;
        $carts = Cart::where('client_id', $this->id)->whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->get();
        foreach ($carts as $cart) {
            foreach ($cart->ProductsCart as $product) {
                $total += $product->quantity * $product->setted_price;
            }
            // foreach ($cart->CartPaymentMethod as $pm) {
            //     $total -= $pm->amount;
            // }
        }
        // $transfers = Transfer::where('client_id', $this->id)->whereMonth('received_from', date('m', strtotime('-1 month')))->whereYear('received_from', date('Y', strtotime('-1 month')))->get();
        // foreach ($transfers as $transfer) {
        //     $total -= $transfer->amount;
        // }
        $abono = AbonoClient::where('client_id', $this->id)->whereMonth('created_at', date('m', strtotime('-1 month')))->whereYear('created_at', date('Y', strtotime('-1 month')))->first();
        if ($abono) {
            $total += $abono->setted_price;
        }
        return $total;
    }

    public function getLastCart($route_id)
    {
        return Cart::where('client_id', $this->id)->where('route_id', '!=', $route_id)->where('is_static', false)->orderBy('created_at', 'desc')->first();
    }

    public function staticRoutesWithUserAndDayOfWeek()
    {
        $cartIds = $this->carts->pluck('id');
        $staticRoutes = Route::whereIn('id', function ($query) use ($cartIds) {
            $query->select('route_id')
                ->from('carts')
                ->whereIn('id', $cartIds)
                ->groupBy('route_id');
        })
        ->where('is_static', true)
        ->get(['user_id', 'day_of_week']);

        foreach ($staticRoutes as $route) {
            $route->user_name = User::find($route->user_id)->name;
        }

        return $staticRoutes;
    }
}
