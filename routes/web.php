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
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth', 'admin'])->group(function () {

    Route::view('/dealers/index', 'dealers.index');
    Route::view('/dealer/details', 'dealers.details');
    Route::view('/dealer/edit', 'dealers.edit');
    
    
    Route::get('/product/index', [App\Http\Controllers\ProductController::class, 'index']);
    Route::post('/product/create', [App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/product/edit', [App\Http\Controllers\ProductController::class, 'update']);
    Route::view('/product/stats', 'products.stats');
    
    Route::get('/client/index', [App\Http\Controllers\ClientController::class, 'index']);
    Route::post('/client/create', [App\Http\Controllers\ClientController::class, 'store']);
    Route::post('/client/edit', [App\Http\Controllers\ClientController::class, 'update']);
    Route::view('/clients/details', 'clients.details');
    Route::view('/clients/invoice', 'clients.invoice');
    
    Route::get('/route/index', [App\Http\Controllers\RouteController::class, 'index']);
    Route::post('/route/create', [App\Http\Controllers\RouteController::class, 'store']);
    Route::post('/route/edit', [App\Http\Controllers\RouteController::class, 'update']);
    Route::view('/route/details', 'routes.details');
    Route::view('/route/new', 'routes.new');
    Route::view('/routes/cart', 'routes.cart');
    Route::view('/routes/details', 'routes.details');

    Route::get('/cart/index', [App\Http\Controllers\CartController::class, 'index']);
    Route::post('/cart/create', [App\Http\Controllers\CartController::class, 'store']);
    Route::post('/cart/edit', [App\Http\Controllers\CartController::class, 'update']);

});

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

});

