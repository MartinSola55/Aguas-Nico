<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientMachine extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'machine_id',
        'quantity',
        'price',
    ];

    public function Client()
    {
        return $this->belongsTo(Client::class);
    }

    public function Machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
