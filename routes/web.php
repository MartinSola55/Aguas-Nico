<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();



Route::view('/products/stats', 'products.stats');
Route::view('/dealers/details', 'dealers.details');
Route::view('/dealers/edit', 'dealers.edit');
Route::view('/routes/details', 'routes.details');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::view('/products/create', 'products.create');
    Route::view('/products/edit', 'products.edit');
    Route::get('/product/index', [App\Http\Controllers\ProductController::class, 'index']);
    Route::post('/product/create', [App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/product/edit', [App\Http\Controllers\ProductController::class, 'store']);

});


