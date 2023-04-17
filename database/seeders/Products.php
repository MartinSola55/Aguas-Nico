<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Products extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Botella de Agua 2L',
            'stock' => '100',
            'price' => 250,
        ]);

        Product::create([
            'name' => 'Bidon de Agua 5L',
            'stock' => '100',
            'price' => 500,
        ]);

        Product::create([
            'name' => 'Bidon de Agua 15L',
            'stock' => '100',
            'price' => 1300,
        ]);
    }
}
