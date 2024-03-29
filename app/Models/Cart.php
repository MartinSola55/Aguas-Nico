<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'route_id',
        'client_id',
        'priority',
        'state',
        'is_static',
        'take_debt',
        'created_at',
        'updated_at',
    ];

    public function Route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function ProductsCart()
    {
        return $this->hasMany(ProductsCart::class, 'cart_id');
    }

    public function CartPaymentMethod()
    {
        return $this->hasMany(CartPaymentMethod::class, 'cart_id');
    }

    public function AbonoClient()
    {
        return $this->hasOne(AbonoClient::class, 'cart_id');
    }

    public function AbonoLog()
    {
        return $this->hasOne(AbonoLog::class, 'cart_id');
    }

    public function StockLogs()
    {
        return $this->hasMany(StockLog::class, 'cart_id');
    }

    public function RenewMachine()
    {
        return $this->hasOne(ClientMachine::class, 'cart_id');
    }
}

// state
// 0 (por defecto) = no confirmado
// 1 = Confirmado (bajo al menos un prod)
// 2 = no estaba
// 3 = no necesitaba
// 4 = Vacaciones
