<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsClient extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products_client')->insert([
            'client_id' => 1,
            'product_id' => 1,
        ]);
        DB::table('products_client')->insert([
            'client_id' => 1,
            'product_id' => 2,
        ]);
        DB::table('products_client')->insert([
            'client_id' => 1,
            'product_id' => 3,
        ]);
        DB::table('products_client')->insert([
            'client_id' => 2,
            'product_id' => 1,
        ]);
        DB::table('products_client')->insert([
            'client_id' => 2,
            'product_id' => 2,
        ]);
    }
}
