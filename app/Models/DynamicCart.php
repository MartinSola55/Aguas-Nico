<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicCart extends Model
{
    use HasFactory;
    protected $fillable = [
        'journey_id',
        'client_id',
        'priority',
        'state',
    ];

    public function Journey()
    {
        return $this->belongsTo(Journey::class, 'journey_id');
    }

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function DynamicCartProduct()
    {
        return $this->hasMany(DynamicCartProduct::class, 'dynamic_cart_id');
    }

    public function DynamicCartPaymentMethod()
    {
        return $this->hasMany(DynamicCartProduct::class, 'dynamic_cart_id');
    }
}
