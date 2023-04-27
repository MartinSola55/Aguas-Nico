<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicCartProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'dynamic_cart_id',
        'quantity',
        'setted_price',
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function DynamicCart()
    {
        return $this->belongsTo(DynamicCart::class, 'dynamic_cart_id');
    }
}
