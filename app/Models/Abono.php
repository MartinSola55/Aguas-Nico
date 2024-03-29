<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'updated_at',
        'is_active'
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
