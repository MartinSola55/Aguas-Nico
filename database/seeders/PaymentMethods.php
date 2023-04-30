<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethods extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create([
            'method' => 'Efectivo'
        ]);
        PaymentMethod::create([
            'method' => 'Transferencia'
        ]);
        PaymentMethod::create([
            'method' => 'Mercado Pago'
        ]);
        PaymentMethod::create([
            'method' => 'Cheque'
        ]);
    }
}
