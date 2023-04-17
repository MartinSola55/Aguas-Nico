<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'route_id',
        'client_id',
        'state',
        'start_date',
        'end_date',
    ];

    public function ProductsCart()
    {
        return $this->hasMany(ProductCart::class, 'cart_id');
    }

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // public function Total()
    // {
    //     $total = 0;
    //     foreach ($this->ProductsCart() as $product) {
    //         $total += $product->quantity * $product->Product->price;
    //     }
    //     return $total;
    // }

    public function Total()
    {
        return $this->ProductsCart()->sum(function($pc) {
            return $pc->quantity * $pc->Product->price;
        });
    }
}
