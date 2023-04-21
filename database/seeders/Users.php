<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Nicolas Gonzalez',
            'email' => 'n@g',
            'rol_id' => 1,
            'password' => bcrypt('12345678'),
        ]);
        User::create([
            'name' => 'Marcelo Gonzalez',
            'email' => 'm@g',
            'rol_id' => 2,
            'password' => bcrypt('12345678'),
        ]);
    }
}
