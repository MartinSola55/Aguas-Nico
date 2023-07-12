<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;
    protected $enumOptions = [
        0 => 'Loaded',
        1 => 'Returned',
    ];

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

    public function getLRAttribute($value)
    {
        return $this->enumOptions[$value];
    }

    public function setLRAttribute($value)
    {
        $this->attributes['l_r'] = array_search($value, $this->enumOptions);
    }

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
}