<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BottleClient extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'client_id';

    protected $fillable = [
        'client_id',
        'bottle_types_id',
        'stock',
    ];

    public function BottleType()
    {
        return $this->belongsTo(BottleType::class, 'bottle_types_id');
    }
}
