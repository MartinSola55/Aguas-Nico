<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'cart_id',
        'product_id',
        'bottle_types_id',
        'quantity',
        'l_r',
        'created_at',
        'updated_at',
    ];

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function Cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function BottleType()
    {
        return $this->belongsTo(BottleType::class, 'bottle_types_id');
    }

    public function Route_id()
    {
        return $this->Cart->Route->id;
    }
}


//DEVUELVE = 1 Return
//Preste = 0 load
