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
        'priority',
        'state',
        'is_static',
    ];

    public function Route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function ProductsCart()
    {
        return $this->hasMany(ProductsCart::class, 'cart_id');
    }

    public function CartPaymentMethod()
    {
        return $this->hasMany(CartPaymentMethod::class, 'cart_id');
    }
}
