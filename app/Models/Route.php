<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_date',
        'end_date',
        'is_static',
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
        $carts = $this->Carts->Load('ProductsCart', 'ProductsCart.Product');
        foreach ($carts as $cart) {
            foreach ($cart->ProductsCart as $pc) {
                $info['total_collected'] += ($pc->quantity_sent * $pc->Product->price) ?? 0;
            }
            if ($cart->state !== 0) {
                $info['state'] = "En reparto";
                $info['completed_carts']++;
                if ($info['completed_carts'] === $info['total_carts']) {
                    $info['state'] = "Completado";
                }
            }
        }
        return $info;
    }
}
