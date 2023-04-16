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
        'delivered',
        'start_date',
        'end_date',
    ];

    public function ProductsCart()
    {
        return $this->hasMany(ProductCart::class, 'cart_id');
    }
}
