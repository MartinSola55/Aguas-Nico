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
    ];

    public function User()
    {
        return $this->belongsTo(Route::class, 'user_id');
    }

    public function Products()
    {
        return $this->hasManyThrough(Product::class, ProductClient::class, 'client_id', 'id', 'id', 'product_id');
    }
}
