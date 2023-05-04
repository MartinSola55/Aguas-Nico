<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Clients extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::create([
            'name' => 'Martin Sola',
            'adress' => 'Rivadavia 1097',
            'dni' => '42559237',
            'phone' => '3404437748',
            'email' => 'payopepe2011@gmail.com',
            'observation' => 'Cuidado con el perro',
            'user_id' => 2,
        ]);

        Client::create([
            'name' => 'Agustin Bettig',
            'adress' => 'Colon 884',
            'dni' => '41637248',
            'phone' => '3404418576',
            'email' => 'agustinbettig@gmail.com',
            'invoice' => 1,
            'user_id' => 2,
        ]);
    }
}
