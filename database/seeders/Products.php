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
            'name' => 'Máquina frío calor',
            'stock' => '35',
            'price' => 5000,
        ]);

        Product::create([
            'name' => 'Bidon de Agua 12L',
            'stock' => '130',
            'price' => 500,
        ]);

        Product::create([
            'name' => 'Bidon de Agua 15L',
            'stock' => '170',
            'price' => 1300,
        ]);
    }
}
