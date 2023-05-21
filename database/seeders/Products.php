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
            'name' => 'Máquina frío/calor',
            'price' => 5000,
        ]);

        Product::create([
            'name' => 'Bidon de Agua 12L',
            'price' => 500,
        ]);

        Product::create([
            'name' => 'Bidon de Agua 20L',
            'price' => 1300,
        ]);

        Product::create([
            'name' => 'Soda',
            'price' => 450,
        ]);
    }
}
