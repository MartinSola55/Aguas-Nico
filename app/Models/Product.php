<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'bottle_type_id',
        'is_active'
    ];

    public function Clients()
    {
        return $this->belongsToMany(Client::class);
    }

    public function bottleType()
    {
        return $this->belongsTo(BottleType::class, 'bottle_type_id');
    }
}
