<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDispatched extends Model
{
    use HasFactory;
    protected $table = 'products_dispatched';
    protected $fillable = [
        'product_id',
        'route_id',
        'quantity',
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

    public function Route()
    {
        return $this->belongsTo(Route::class);
    }
}
