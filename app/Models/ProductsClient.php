<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsClient extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'products_client';
    protected $fillable = [
        'client_id',
        'product_id',
        'stock',
    ];


    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
