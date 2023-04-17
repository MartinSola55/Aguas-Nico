<?php

namespace Database\Seeders;

use App\Models\ProductCart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsCarts extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCart::create([
            'product_id' => 1,
            'cart_id' => 1,
            'quantity' => 2,
        ]);

        ProductCart::create([
            'product_id' => 2,
            'cart_id' => 1,
            'quantity' => 1,
        ]);

        ProductCart::create([
            'product_id' => 3,
            'cart_id' => 2,
            'quantity' => 1,
        ]);
    }
}
