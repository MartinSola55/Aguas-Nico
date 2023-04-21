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
            'setted_price' => '250',
        ]);

        ProductCart::create([
            'product_id' => 2,
            'cart_id' => 1,
            'quantity' => 1,
            'setted_price' => '500',
        ]);

        ProductCart::create([
            'product_id' => 3,
            'cart_id' => 2,
            'quantity' => 1,
            'setted_price' => '1300',
        ]);
    }
}
