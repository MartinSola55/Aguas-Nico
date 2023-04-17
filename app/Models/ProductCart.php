<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
    use HasFactory;
    protected $table = 'products_carts';
    protected $fillable = [
        'product_id',
        'cart_id',
        'quantity',
        'quantity_sent',
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
