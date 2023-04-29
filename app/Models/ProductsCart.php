<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsCart extends Model
{
    use HasFactory;
    protected $table = 'products_cart';
    protected $fillable = [
        'product_id',
        'cart_id',
        'quantity',
        'setted_price',
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}
