<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbonoLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_id',
        'abono_clients_id',
        'quantity',
        'created_at',
        'updated_at',
    ];

    public function Client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function AbonoClient()
    {
        return $this->belongsTo(AbonoClient::class, 'abono_clients_id');
    }


}
