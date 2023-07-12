<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbonoClient extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'abono_id',
        'available',
        'cart_id',
        'setted_price',
        'created_at',
        'updated_at',
    ];

    public function Abono()
    {
        return $this->belongsTo(Abono::class, 'abono_id');
    }
}
