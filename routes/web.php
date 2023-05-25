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

    // HOME
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/invoice', [App\Http\Controllers\HomeController::class, 'invoice'])->name('invoice');
    Route::get('/home/searchAllSales', [App\Http\Controllers\HomeController::class, 'searchAllSales']);
    Route::get('/stats', [App\Http\Controllers\DealerController::class, 'statistics'])->name('stats');

    // DEALER
    Route::get('/dealer/index', [App\Http\Controllers\DealerController::class, 'index']);
    Route::get('/dealer/details/{id}', [App\Http\Controllers\DealerController::class, 'show'])->name('dealer.details');

    // PRODUCT
    Route::get('/product/index', [App\Http\Controllers\ProductController::class, 'index']);
    Route::post('/product/create', [App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/product/edit', [App\Http\Controllers\ProductController::class, 'update']);
    Route::get('/product/stats/{id}', [App\Http\Controllers\ProductController::class, 'stats'])->name('product.stats');

    // CLIENT
    Route::get('/client/index', [App\Http\Controllers\ClientController::class, 'index']);
    Route::get('/client/details/{id}', [App\Http\Controllers\ClientController::class, 'show'])->name('client.details');
    Route::get('/client/showInvoice/{id}', [App\Http\Controllers\ClientController::class, 'show_invoice'])->name('client.invoice');
    Route::post('/client/create', [App\Http\Controllers\ClientController::class, 'store']);
    Route::get('/client/searchSales', [App\Http\Controllers\ClientController::class, 'searchSales']);
    Route::post('/client/edit', [App\Http\Controllers\ClientController::class, 'update']);
    Route::post('/client/updateInvoiceData', [App\Http\Controllers\ClientController::class, 'updateInvoiceData']);
    Route::get('/client/products/{client}', [App\Http\Controllers\ClientController::class, 'getProducts']);
    Route::post('/client/updateProducts', [App\Http\Controllers\ClientController::class, 'updateProducts']);
    Route::post('/client/setIsActive', [App\Http\Controllers\ClientController::class, 'setIsActive']);

    // ROUTE
    Route::get('/route/index', [App\Http\Controllers\RouteController::class, 'index']);
    Route::post('/route/create', [App\Http\Controllers\RouteController::class, 'store']);
    Route::get('/route/showRoutes', [App\Http\Controllers\RouteController::class, 'show']);
    Route::get('/route/details/{id}', [App\Http\Controllers\RouteController::class, 'details'])->name('route.details');
    Route::get('/route/new', [App\Http\Controllers\RouteController::class, 'new']);
    Route::get('/route/{id}/newCart', [App\Http\Controllers\RouteController::class, 'newCart']);
    Route::get('/route/{id}/newManualCart', [App\Http\Controllers\RouteController::class, 'newManualCart']);
    Route::post('/route/createManualCart', [App\Http\Controllers\RouteController::class, 'createManualCart']);
    //ProductDispatchedController
    Route::post('/route/updateDispatched', [App\Http\Controllers\RouteController::class, 'updateDispatched']);
    //Agregar/actualizar clientes en reparto
    Route::post('/route/updateClients', [App\Http\Controllers\RouteController::class, 'updateClients']);
    Route::post('/route/delete', [App\Http\Controllers\RouteController::class, 'delete']);

    // CART
    Route::get('/cart/index', [App\Http\Controllers\CartController::class, 'index']);
    Route::post('/cart/confirm', [App\Http\Controllers\CartController::class, 'confirm']);
    Route::post('/cart/delete', [App\Http\Controllers\CartController::class, 'delete']);

    // EXPENSES
    Route::post('/expense/create', [App\Http\Controllers\ExpenseController::class, 'store']);
    Route::post('/expense/delete', [App\Http\Controllers\ExpenseController::class, 'delete']);

});

// EMPLOYEE
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/route/details/{id}', [App\Http\Controllers\RouteController::class, 'details'])->name('route.details');
    Route::get('/route/index', [App\Http\Controllers\RouteController::class, 'index']);
    Route::get('/route/getProductsClient', [App\Http\Controllers\RouteController::class, 'getProductsClient']);
    Route::get('/route/showRoutes', [App\Http\Controllers\RouteController::class, 'show']);
    Route::get('/route/{id}/newCart', [App\Http\Controllers\RouteController::class, 'newCart']);
    Route::post('/route/updateReturned', [App\Http\Controllers\RouteController::class, 'updateReturned']);


    // Cambiar estado del carrito
    Route::post('/cart/changeState', [App\Http\Controllers\CartController::class, 'changeState']);

    //Crear pedido
    Route::post('/route/createNew', [App\Http\Controllers\RouteController::class, 'create']);

    //Confirmar carrito
    Route::post('/cart/confirm', [App\Http\Controllers\CartController::class, 'confirm']);

    //Agregar/actualizar clientes en reparto
    Route::post('/route/addClients', [App\Http\Controllers\RouteController::class, 'addClients']);

    // EXPENSES
    Route::get('/expense/index', [App\Http\Controllers\ExpenseController::class, 'index']);
    Route::post('/expense/create', [App\Http\Controllers\ExpenseController::class, 'store']);
    Route::get('/expense/searchExpenses', [App\Http\Controllers\ExpenseController::class, 'searchExpenses']);
});

