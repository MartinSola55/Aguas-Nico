<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'start_daytime',
        'end_daytime',
    ];

    public function Carts()
    {
        return $this->hasMany(Cart::class, 'route_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Info()
    {
        $info = [];
        $info['state'] = "En Deposito";
        $info['total_carts'] = $this->Carts()->count();
        $info['completed_carts'] = 0;
        $info['total_collected'] = 0;
        $countState = 0;
        foreach ($this->Carts() as $cart) {
            $countState ++;
            if ($cart->state !== 0) {
                $info['state'] = "En Reparto";
                $info['completed_carts'] ++;
                $info['total_collected'] += $cart->Total();
                if ($countState === $this->Carts()->count()) {
                    $info['state'] = "Completado";
                }
            }
        }
    }

}
