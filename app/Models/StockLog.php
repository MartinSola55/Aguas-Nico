<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'cart_id',
        'product_id',
        'bottle_types_id',
        'quantity',
        'l_r',
        'created_at',
        'updated_at',
    ];
}
