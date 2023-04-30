<?php

namespace Database\Seeders;

use App\Models\Route;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Routes extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Route::create([
            'user_id' => 2,
            'day_of_week' => 1,
            'is_static' => true,
        ]);
        Route::create([
            'user_id' => 2,
            'day_of_week' => 2,
            'is_static' => true,
        ]);
        Route::create([
            'user_id' => 2,
            'day_of_week' => 3,
            'is_static' => true,
        ]);
        Route::create([
            'user_id' => 2,
            'day_of_week' => 4,
            'is_static' => true,
        ]);
        Route::create([
            'user_id' => 2,
            'day_of_week' => 5,
            'is_static' => true,
        ]);
        // User 3
        Route::create([
            'user_id' => 3,
            'day_of_week' => 1,
            'is_static' => true,
        ]);
        Route::create([
            'user_id' => 3,
            'day_of_week' => 2,
            'is_static' => true,
        ]);
        Route::create([
            'user_id' => 3,
            'day_of_week' => 3,
            'is_static' => true,
        ]);
        Route::create([
            'user_id' => 3,
            'day_of_week' => 4,
            'is_static' => true,
        ]);
        Route::create([
            'user_id' => 3,
            'day_of_week' => 5,
            'is_static' => true,
        ]);

        // Dynamic routes
        Route::create([
            'user_id' => 2,
            'day_of_week' => 1,
            'start_date' => '2023-05-01',
            'end_date' => '2023-05-01',
            'is_static' => false,
        ]);
        Route::create([
            'user_id' => 2,
            'day_of_week' => 2,
            'start_date' => '2023-05-02',
            'end_date' => '2023-05-02',
            'is_static' => false,
        ]);
        Route::create([
            'user_id' => 2,
            'day_of_week' => 3,
            'start_date' => '2023-05-03',
            'end_date' => '2023-05-03',
            'is_static' => false,
        ]);
        Route::create([
            'user_id' => 2,
            'day_of_week' => 4,
            'start_date' => '2023-05-04',
            'end_date' => '2023-05-04',
            'is_static' => false,
        ]);
        Route::create([
            'user_id' => 2,
            'day_of_week' => 5,
            'start_date' => '2023-05-05',
            'end_date' => '2023-05-05',
            'is_static' => false,
        ]);
        // User 3
        Route::create([
            'user_id' => 3,
            'day_of_week' => 1,
            'start_date' => '2023-05-01',
            'end_date' => '2023-05-01',
            'is_static' => false,
        ]);
        Route::create([
            'user_id' => 3,
            'day_of_week' => 2,
            'start_date' => '2023-05-02',
            'end_date' => '2023-05-02',
            'is_static' => false,
        ]);
        Route::create([
            'user_id' => 3,
            'day_of_week' => 3,
            'start_date' => '2023-05-03',
            'end_date' => '2023-05-03',
            'is_static' => false,
        ]);
        Route::create([
            'user_id' => 3,
            'day_of_week' => 4,
            'start_date' => '2023-05-04',
            'end_date' => '2023-05-04',
            'is_static' => false,
        ]);
        Route::create([
            'user_id' => 3,
            'day_of_week' => 5,
            'start_date' => '2023-05-05',
            'end_date' => '2023-05-05',
            'is_static' => false,
        ]);
    }
}
