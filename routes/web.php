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

    Route::view('/dealer/index', 'dealers.index');
    Route::view('/dealer/details', 'dealers.details');
    Route::view('/dealer/edit', 'dealers.edit');


    Route::get('/product/index', [App\Http\Controllers\ProductController::class, 'index']);
    Route::post('/product/create', [App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/product/edit', [App\Http\Controllers\ProductController::class, 'update']);
    Route::get('/product/stats/{id}', [App\Http\Controllers\ProductController::class, 'stats'])->name('product.stats');
    Route::view('/product/stats', 'products.stats');
    Route::view('/product/new', 'products.new');

    Route::get('/client/index', [App\Http\Controllers\ClientController::class, 'index']);
    Route::get('/client/details/{id}', [App\Http\Controllers\ClientController::class, 'show'])->name('client.details');
    Route::get('/client/showInvoice/{id}', [App\Http\Controllers\ClientController::class, 'show_invoice'])->name('client.invoice');
    Route::post('/client/searchSales', [App\Http\Controllers\ClientController::class, 'searchSales']);
    Route::post('/client/create', [App\Http\Controllers\ClientController::class, 'store']);
    Route::post('/client/edit', [App\Http\Controllers\ClientController::class, 'update']);

    Route::get('/route/index', [App\Http\Controllers\RouteController::class, 'index']);
    Route::post('/route/create', [App\Http\Controllers\RouteController::class, 'store']);
    Route::post('/route/edit', [App\Http\Controllers\RouteController::class, 'update']);
    Route::get('/route/showRoutes', [App\Http\Controllers\RouteController::class, 'show']);
    Route::get('/route/details/{id}', [App\Http\Controllers\RouteController::class, 'details']);
    Route::get('/route/new', [App\Http\Controllers\RouteController::class, 'new']);
    Route::get('/route/{id}/newCart', [App\Http\Controllers\RouteController::class, 'newCart']);
    Route::get('/route/getProductCarts', [App\Http\Controllers\RouteController::class, 'getProductCarts']);
    //Confirmar pedido
    Route::post('/route/confirm', [App\Http\Controllers\RouteController::class, 'confirm']);

    Route::get('/cart/index', [App\Http\Controllers\CartController::class, 'index']);
    Route::post('/cart/create', [App\Http\Controllers\CartController::class, 'store']);
    Route::post('/cart/edit', [App\Http\Controllers\CartController::class, 'update']);

});

Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

});

