<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(Roles::class);
        $this->call(Users::class);
        $this->call(Products::class);
        $this->call(Clients::class);
        $this->call(Routes::class);
        $this->call(ProductsClient::class);
        $this->call(Carts::class);
        $this->call(ProductsCarts::class);
        $this->call(PaymentMethods::class);
        $this->call(CartPaymentMethods::class);
    }
}
