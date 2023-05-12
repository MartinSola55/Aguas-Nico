<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Carts extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Statics
        Cart::create([
            'route_id' => 1,
            'client_id' => 1,
            'priority' => 1,
            'state' => null,
            'is_static' => true,
        ]);
        Cart::create([
            'route_id' => 1,
            'client_id' => 2,
            'priority' => 2,
            'state' => null,
            'is_static' => true,
        ]);
        Cart::create([
            'route_id' => 6,
            'client_id' => 1,
            'priority' => 1,
            'state' => null,
            'is_static' => true,
        ]);
        Cart::create([
            'route_id' => 6,
            'client_id' => 2,
            'priority' => 2,
            'state' => null,
            'is_static' => true,
        ]);

        // Dynamics
        Cart::create([
            'route_id' => 11,
            'client_id' => 1,
            'priority' => 1,
            'state' => 1,
            'is_static' => false,
        ]);
        Cart::create([
            'route_id' => 11,
            'client_id' => 2,
            'priority' => 2,
            'state' => 0,
            'is_static' => false,
        ]);
    }
}
