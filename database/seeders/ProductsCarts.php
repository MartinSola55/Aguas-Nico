<?php

namespace Database\Seeders;

use App\Models\ProductsCart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsCarts extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductsCart::create([
            'product_id' => 1,
            'cart_id' => 5,
            'quantity' => 2,
            'setted_price' => '5000',
        ]);
        ProductsCart::create([
            'product_id' => 2,
            'cart_id' => 5,
            'quantity' => 7,
            'setted_price' => '450',
        ]);
    }
}
