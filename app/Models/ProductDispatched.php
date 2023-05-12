<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDispatched extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'route_id',
        'quantity',
    ];

}
