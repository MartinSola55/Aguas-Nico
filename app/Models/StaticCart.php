<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticCart extends Model
{
    use HasFactory;
    protected $fillable = [
        'route_id',
        'client_id',
        'priority',
    ];

    public function Route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
