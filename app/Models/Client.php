<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'adress',
        'phone',
        'email',
        'debt',
        'dni',
        'invoice',
        'observation',
    ];
}
