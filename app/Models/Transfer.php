<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'amount',
        'client_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
