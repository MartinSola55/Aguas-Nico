<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'adress',
        'phone',
        'email',
        'debt',
        'dni',
        'invoice',
        'is_active',
        'observation',
        'user_id',
        'invoice_type',
        'business_name',
        'tax_condition',
        'cuit',
        'tax_address',
        'abono_id',
    ];

    public function Products()
    {
        return $this->hasManyThrough(Product::class, ProductsClient::class, 'client_id', 'id', 'id', 'product_id');
    }

    public function ProductsClient()
    {
        return $this->hasMany(ProductsClient::class, 'client_id');
    }

    public function Carts()
    {
        return $this->hasMany(Cart::class, 'client_id');
    }

    public function BottleClient()
    {
        return $this->hasMany(BottleClient::class, 'client_id');
    }
}
