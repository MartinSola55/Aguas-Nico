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
    //Route::get('/invoice', [App\Http\Controllers\HomeController::class, 'invoice'])->name('invoice');
    Route::get('/home/searchRoutes', [App\Http\Controllers\HomeController::class, 'searchRoutes']);
    Route::get('/stats', [App\Http\Controllers\DealerController::class, 'statistics'])->name('stats');

    // TRANSFER
    Route::get('/transfer/index', [App\Http\Controllers\TransferController::class, 'index']);
    Route::get('/transfer/searchTransfers', [App\Http\Controllers\TransferController::class, 'searchTransfers']);
    Route::get('/transfer/searchClients', [App\Http\Controllers\TransferController::class, 'searchClients']);
    Route::post('/transfer/delete', [App\Http\Controllers\TransferController::class, 'delete']);
    Route::post('/transfer/create', [App\Http\Controllers\TransferController::class, 'store']);
    Route::post('/transfer/edit', [App\Http\Controllers\TransferController::class, 'update']);
    Route::get('/transfer/route/{id}', [App\Http\Controllers\TransferController::class, 'getTransfersRoute']);

    // DEALER
    Route::get('/dealer/index', [App\Http\Controllers\DealerController::class, 'index']);
    Route::get('/dealer/details/{id}', [App\Http\Controllers\DealerController::class, 'show'])->name('dealer.details');
    Route::get('/dealer/getPendingCarts', [App\Http\Controllers\DealerController::class, 'getPendingCarts']);
    Route::get('/dealer/searchClients', [App\Http\Controllers\DealerController::class, 'searchClients']);
    Route::get('/dealer/searchClientsMachines', [App\Http\Controllers\DealerController::class, 'searchClientsMachines']);
    Route::get('/dealer/searchClientsAbono', [App\Http\Controllers\DealerController::class, 'searchClientsAbono']);
    Route::get('/dealer/searchProductsSold', [App\Http\Controllers\DealerController::class, 'searchProductsSold']);
    Route::get('/dealer/searchClientsNotVisited', [App\Http\Controllers\DealerController::class, 'searchClientsNotVisited']);

    // PRODUCT
    Route::get('/product/index', [App\Http\Controllers\ProductController::class, 'index']);
    Route::post('/product/create', [App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/product/edit', [App\Http\Controllers\ProductController::class, 'update']);
    Route::get('/product/stats/{id}', [App\Http\Controllers\ProductController::class, 'stats'])->name('product.stats');
    Route::get('/product/clients/{id}', [App\Http\Controllers\ProductController::class, 'getClients']);

    // CLIENT
    Route::get('/client/index', [App\Http\Controllers\ClientController::class, 'index']);
    Route::get('/client/details/{id}', [App\Http\Controllers\ClientController::class, 'show'])->name('client.details');
    //Route::get('/client/showInvoice/{id}', [App\Http\Controllers\ClientController::class, 'showInvoice'])->name('client.invoice');
    Route::post('/client/create', [App\Http\Controllers\ClientController::class, 'store']);
    Route::get('/client/searchSales', [App\Http\Controllers\ClientController::class, 'searchSales']);
    Route::post('/client/edit', [App\Http\Controllers\ClientController::class, 'update']);
    Route::post('/client/updateInvoiceData', [App\Http\Controllers\ClientController::class, 'updateInvoiceData']);
    Route::get('/client/products/{client}', [App\Http\Controllers\ClientController::class, 'getProducts']);
    Route::post('/client/updateProducts', [App\Http\Controllers\ClientController::class, 'updateProducts']);
    Route::post('/client/updateAbono', [App\Http\Controllers\ClientController::class, 'updateAbono']);
    Route::post('/client/updateMachine', [App\Http\Controllers\ClientController::class, 'updateMachine']);
    Route::post('/client/setIsActive', [App\Http\Controllers\ClientController::class, 'setIsActive']);
    Route::get('/client/getHistory/{id}', [App\Http\Controllers\ClientController::class, 'getHistory']);
    Route::get('/client/getStockHistory', [App\Http\Controllers\ClientController::class, 'stockHistory']);

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
    Route::get('/route/getProductsDispatched/{route}', [App\Http\Controllers\RouteController::class, 'getProducts4dispatch']);
    //Agregar/actualizar clientes en reparto
    Route::post('/route/updateClients', [App\Http\Controllers\RouteController::class, 'updateClients']);
    Route::post('/route/delete', [App\Http\Controllers\RouteController::class, 'delete']);

    // FACTURA
    Route::get('/invoice/generateInvoice/{id}', [App\Http\Controllers\InvoiceController::class, 'generateInvoice']);
    Route::get('/invoice/searchAllSales', [App\Http\Controllers\InvoiceController::class, 'searchAllSales']);

    // CART
    Route::get('/cart/index', [App\Http\Controllers\CartController::class, 'index']);
    Route::post('/cart/confirm', [App\Http\Controllers\CartController::class, 'confirm']);
    Route::post('/cart/delete', [App\Http\Controllers\CartController::class, 'delete']);

    // EXPENSES
    Route::post('/expense/create', [App\Http\Controllers\ExpenseController::class, 'store']);
    Route::post('/expense/delete', [App\Http\Controllers\ExpenseController::class, 'delete']);

    //ABONOS
    Route::post('/abono/renew', [App\Http\Controllers\AbonoClientController::class, 'store']);
    Route::get('/abono/index', [App\Http\Controllers\AbonoController::class, 'index']);
    Route::get('/abono/clientes', [App\Http\Controllers\AbonoController::class, 'abonoClients'])->name('abono.clientes');
    Route::post('/abono/edit', [App\Http\Controllers\AbonoController::class, 'update']);
    Route::post('/abono/updatePrice', [App\Http\Controllers\AbonoController::class, 'updatePrice']);
    Route::get('/abono/clients/{id}', [App\Http\Controllers\AbonoController::class, 'getClients']);

    //MACHINES
    Route::get('/machine/index', [App\Http\Controllers\MachineController::class, 'index']);
    Route::post('/machine/updatePrice', [App\Http\Controllers\MachineController::class, 'updatePrice']);
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

    // Buscar clientes
    Route::get('/cart/searchClients', [App\Http\Controllers\CartController::class, 'searchClients']);

    // Cambiar estado del carrito
    Route::post('/cart/changeState', [App\Http\Controllers\CartController::class, 'changeState']);

    //Crear pedido
    Route::post('/route/createNew', [App\Http\Controllers\RouteController::class, 'create']);

    //Confirmar carrito
    Route::post('/cart/confirm', [App\Http\Controllers\CartController::class, 'confirm']);

    //Editar carrito
    Route::post('/cart/edit', [App\Http\Controllers\CartController::class, 'edit']);

    //Devolución en carrito
    Route::post('/cart/return', [App\Http\Controllers\CartController::class, 'returnStock']);

    //Agregar/actualizar clientes en reparto
    Route::post('/route/addClients', [App\Http\Controllers\RouteController::class, 'addClients']);

    // EXPENSES
    Route::get('/expense/index', [App\Http\Controllers\ExpenseController::class, 'index']);
    Route::post('/expense/create', [App\Http\Controllers\ExpenseController::class, 'store']);
    Route::get('/expense/searchExpenses', [App\Http\Controllers\ExpenseController::class, 'searchExpenses']);

    // CLIENT
    Route::get('/client/index', [App\Http\Controllers\ClientController::class, 'index']);
    Route::post('/client/create', [App\Http\Controllers\ClientController::class, 'store']);
    Route::get('/client/products/{client}', [App\Http\Controllers\ClientController::class, 'getStock']);

    //ABONOS
    Route::post('/abono/renew', [App\Http\Controllers\AbonoClientController::class, 'store']);
    Route::get('/abono/getlog', [App\Http\Controllers\AbonoClientController::class, 'getLog']);
    Route::get('/client/getHistory/{id}', [App\Http\Controllers\ClientController::class, 'getHistory']);

    //MACHINES
    Route::post('/machine/renew', [App\Http\Controllers\MachineController::class, 'renew']);

});

