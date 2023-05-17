<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartPaymentMethod extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'cart_id',
        'payment_method_id',
        'amount',
    ];

    public function Cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function PaymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
