<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journey extends Model
{
    use HasFactory;
    protected $fillable = [
        'route_id',
        'start_date',
        'end_date',
    ];

    public function Route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }
}
