<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicCartPaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'dynamic_cart_id',
        'payment_method_id',
        'amount',
    ];
    
    public function DynamicCart()
    {
        return $this->belongsTo(DynamicCart::class, 'dynamic_cart_id');
    }

    public function PaymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
