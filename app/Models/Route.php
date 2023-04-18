<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Break_;

class Route extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'start_daytime',
        'end_daytime',
    ];

    public function Carts()
    {
        return $this->hasMany(Cart::class, 'route_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Info()
    {
        $info = [];
        $info['state'] = "En depósito";
        $info['total_carts'] = $this->Carts()->count();
        $info['completed_carts'] = 0;
        $info['total_collected'] = 0;
        $countCarts = 0;
        $carts = $this->with(['Carts', 'Carts.ProductsCart', 'Carts.ProductsCart.Product'])->get()[0];
        $info['QSY'] = $carts;
        foreach ($carts->carts as $cart) {
            foreach ($cart->ProductsCart as $pc) {
                $info['total_collected'] += $pc->quantity_sent * $pc->Product->price;
            }
            $countCarts ++;
            if ($cart->state !== 0) {
                $info['state'] = "En reparto";
                $info['completed_carts'] ++;
                if ($countCarts === $this->Carts()->count()) {
                    $info['state'] = "Completado";
                }
            }
        }
        return $info;
    }
}
