<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtPaymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'cart_id',
        'transfer_id',
        'debt',
        'paid',
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

    public function Transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }
}
