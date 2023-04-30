<?php

namespace Database\Seeders;

use App\Models\CartPaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartPaymentMethods extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cart_payment_methods')->insert([
            'cart_id' => 5,
            'payment_method_id' => 2,
            'amount' => 5000,
        ]);
        DB::table('cart_payment_methods')->insert([
            'cart_id' => 5,
            'payment_method_id' => 1,
            'amount' => 450,
        ]);
    }
}
