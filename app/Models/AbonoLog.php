<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AbonoLog extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'cart_id',
        'abono_clients_id',
        'quantity',
        'created_at',
        'updated_at',
    ];

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function AbonoClient()
    {
        return $this->belongsTo(AbonoClient::class, 'abono_clients_id');
    }

    public function Cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}
