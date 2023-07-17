<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BottleType extends Model
{
    use HasFactory;
    
    public function Products()
    {
        return $this->hasMany(Product::class, 'bottle_type_id');
    }
}
