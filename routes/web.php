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

// ADMIN
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // DEALER
    Route::get('/dealer/index', [App\Http\Controllers\DealerController::class, 'index']);
    Route::get('/dealer/details/{id}', [App\Http\Controllers\DealerController::class, 'show'])->name('dealer.details');

    // PRODUCT
    Route::get('/product/index', [App\Http\Controllers\ProductController::class, 'index']);
    Route::post('/product/create', [App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/product/edit', [App\Http\Controllers\ProductController::class, 'update']);
    Route::get('/product/stats/{id}', [App\Http\Controllers\ProductController::class, 'stats'])->name('product.stats');
    Route::view('/product/new', 'products.new');

    // CLIENT
    Route::get('/client/index', [App\Http\Controllers\ClientController::class, 'index']);
    Route::get('/client/details/{id}', [App\Http\Controllers\ClientController::class, 'show'])->name('client.details');
    Route::get('/client/showInvoice/{id}', [App\Http\Controllers\ClientController::class, 'show_invoice'])->name('client.invoice');
    Route::post('/client/create', [App\Http\Controllers\ClientController::class, 'store']);
    Route::get('/client/searchSales', [App\Http\Controllers\ClientController::class, 'searchSales']);
    Route::post('/client/edit', [App\Http\Controllers\ClientController::class, 'update']);
    Route::get('/client/products/{client}', [App\Http\Controllers\ClientController::class, 'getProducts']);
    Route::post('/client/updateProducts', [App\Http\Controllers\ClientController::class, 'updateProducts']);

    // ROUTE
    Route::get('/route/index', [App\Http\Controllers\RouteController::class, 'index']);
    Route::post('/route/create', [App\Http\Controllers\RouteController::class, 'store']);
    Route::post('/route/edit', [App\Http\Controllers\RouteController::class, 'update']);
    Route::get('/route/showRoutes', [App\Http\Controllers\RouteController::class, 'show']);
    Route::get('/route/details/{id}', [App\Http\Controllers\RouteController::class, 'details'])->name('route.details');
    Route::get('/route/new', [App\Http\Controllers\RouteController::class, 'new']);
    Route::get('/route/{id}/newCart', [App\Http\Controllers\RouteController::class, 'newCart']);
    //Agregar/actualizar clientes en reparto
    Route::post('/route/updateClients', [App\Http\Controllers\RouteController::class, 'updateClients']);

    // CART
    Route::get('/cart/index', [App\Http\Controllers\CartController::class, 'index']);
    Route::post('/cart/create', [App\Http\Controllers\CartController::class, 'store']);
    Route::post('/cart/edit', [App\Http\Controllers\CartController::class, 'update']);
    Route::post('/cart/confirm', [App\Http\Controllers\CartController::class, 'confirm']);

});

// EMPLOYEE
Route::middleware(['auth', 'employee'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/invoice', [App\Http\Controllers\HomeController::class, 'invoice'])->name('invoice');
    Route::get('/route/details/{id}', [App\Http\Controllers\RouteController::class, 'details'])->name('route.details');
    Route::get('/route/index', [App\Http\Controllers\RouteController::class, 'index']);
    Route::get('/route/getProductsClient', [App\Http\Controllers\RouteController::class, 'getProductsClient']);
    Route::get('/route/showRoutes', [App\Http\Controllers\RouteController::class, 'show']);

    // Cambiar estado del carrito
    Route::post('/cart/changeState', [App\Http\Controllers\CartController::class, 'changeState']);

    //Crear pedido
    Route::post('/route/createNew', [App\Http\Controllers\RouteController::class, 'create']);
    
    //Confirmar carrito
    Route::post('/cart/confirm', [App\Http\Controllers\CartController::class, 'confirm']);
    
    //Agregar/actualizar clientes en reparto
    Route::post('/route/updateClients', [App\Http\Controllers\RouteController::class, 'updateClients']);
    
});

