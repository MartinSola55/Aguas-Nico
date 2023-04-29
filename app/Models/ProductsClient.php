<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsClient extends Model
{
    use HasFactory;
    protected $table = 'products_client';
    protected $fillable = [
        'client_id',
        'product_id',
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
