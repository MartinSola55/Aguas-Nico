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
        Cart::create([
            'route_id' => 1,
            'client_id' => 1,
            'start_date' => '2023-04-18 11:50:00',
        ]);

        Cart::create([
            'route_id' => 1,
            'client_id' => 2,
            'start_date' => '2023-04-18 12:20:00',
        ]);
    }
}
