<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expense;
use App\Models\ProductsCart;
use App\Models\Route;
use App\Http\Controllers\RouteController;
use App\Models\AbonoClient;
use App\Models\AbonoLog;
use App\Models\BottleType;
use App\Models\Product;
use App\Models\ProductDispatched;
use App\Models\StockLog;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private function getDate()
    {
        return Carbon::now(new DateTimeZone('America/Argentina/Buenos_Aires'));
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        // Admin
        if ($user->rol_id == '1') {
            // Obtener los repartos del día
            $routes = Route::where('is_static', false)
                ->whereDate('start_date', $this->getDate())
                ->with('Carts')
                ->with('Carts.ProductsCart')
                ->with('Carts.ProductsCart.Product')
                ->with('User')
                ->get();

            // Calcular las ganancias totales del día
            $data = (object) [
                'day_collected' => 0,
                'day_expenses' => Expense::whereDate('created_at', $this->getDate())->orderBy('spent', 'desc')->get(),
                'completed_routes' => 0,
                'pending_routes' => 0,
                'in_deposit_routes' => 0,
                'products' => [],
                'bottles' => [],
                'items' => [],
            ];
            foreach ($routes as $route) {
                $counter = 0;
                $total_carts = $route->Carts()->count();
                $completed_carts = 0;
                foreach ($route->Carts as $cart) {
                    $counter++;
                    // Calcular la cantidad de repartos completados
                    if ($cart->state !== 0) {
                        $completed_carts++;
                    }
                    if ($completed_carts === $total_carts) {
                        $data->completed_routes++;
                    } else if ($counter === $total_carts && $completed_carts === 0) {
                        $data->in_deposit_routes++;
                    }

                    foreach ($cart->CartPaymentMethod as $pm) {
                        $data->day_collected += $pm->amount;
                    }
                }
                if ($route->Carts()->count() === 0) {
                    $data->in_deposit_routes++;
                }
            }
            $data->pending_routes = $routes->count() - $data->completed_routes - $data->in_deposit_routes;

            $cartsIDs = $routes->pluck('Carts')->flatten()->pluck('id');
            $routesIDS = $routes->pluck('id');
            $productsDispatched = ProductDispatched::whereIn('route_id', $routesIDS)->get();

            $stock_logs = StockLog::whereIn('cart_id', $cartsIDs)->get();
            $abono_logs = AbonoLog::whereIn('cart_id', $cartsIDs)->get();

            // Sumar los productos de los abonos
            foreach ($abono_logs as $abono_log) {
                $product = $abono_log->AbonoClient->Abono->Product;
                if ($product->bottle_type_id == null) {
                    $productId = $product->id;
                    $quantity = $abono_log->quantity;

                    $dispatch = $productsDispatched->where('product_id', $productId)->sum('quantity');

                    if (!isset($data->products[$productId])) {
                        $data->products[$productId] = [
                            'id' => $productId,
                            'dispatch' => $dispatch,
                            'name' => $product->name,
                            'sold' => 0,
                            'returned' => 0,
                        ];
                    }
                    $data->products[$productId]['sold'] += $quantity;
                } else {
                    $bottleTypeId = $product->bottle_type_id;
                    $quantity = $abono_log->quantity;

                    $dispatch = $productsDispatched->where('bottle_types_id', $bottleTypeId)->sum('quantity'); 
                    if (!isset($data->bottles[$bottleTypeId])) {
                        $data->bottles[$bottleTypeId] = [
                            'id' => $bottleTypeId,
                            'dispatch' => $dispatch,
                            'name' => $product->bottleType->name,
                            'sold' => 0,
                            'returned' => 0,
                        ];
                    }
                    $data->bottles[$bottleTypeId]['sold'] += $quantity;
                }
            }

            // Sumar los productos del carrito
            foreach ($stock_logs as $log) {
                if ($log->product_id !== null) { // Si es un producto
                    $productId = $log->product_id;
                    $productName = Product::find($productId)->name; // Obtener el nombre del producto desde el modelo "Product"
                    $quantity = $log->quantity;

                    $dispatch = $productsDispatched->where('product_id', $productId)->sum('quantity');
                    
                    if (!isset($data->products[$productId])) {
                        $data->products[$productId] = [
                                'id' => $productId,
                                'dispatch' => $dispatch,
                                'name' => $productName,
                                'sold' => 0,
                                'returned' => 0,
                            ];
                    }

                    if ($log->l_r === 0) { // Sold
                        $data->products[$productId]['sold'] += $quantity;
                    } elseif ($log->l_r === 1) { // Returned
                        $data->products[$productId]['returned'] += $quantity;
                    }
                } elseif ($log->bottle_types_id !== null) { // Si es una botella
                    $bottleTypeId = $log->bottle_types_id;
                    $bottleTypeName = BottleType::find($bottleTypeId)->name; // Obtener el nombre del tipo de botella desde el modelo "BottleType"
                    $quantity = $log->quantity;

                    $dispatch = $productsDispatched->where('bottle_types_id', $bottleTypeId)->sum('quantity');
                    if (!isset($data->bottles[$bottleTypeId])) {
                        $data->bottles[$bottleTypeId] = [
                            'id' => $bottleTypeId,
                            'dispatch' => $dispatch,
                            'name' => $bottleTypeName,
                            'sold' => 0,
                            'returned' => 0,
                        ];
                    }

                    if ($log->l_r === 0) { // Sold
                        $data->bottles[$bottleTypeId]['sold'] += $quantity;
                    } elseif ($log->l_r === 1) { // Returned
                        $data->bottles[$bottleTypeId]['returned'] += $quantity;
                    }
                }
            }
            $data->items = array_merge($data->products, $data->bottles);

            return view('home', compact('routes', 'data'));
        } // Repartidor
        else {
            $routeController = new RouteController();
            $routes = $routeController->getDealerRoutes(date('N'), $user->id);
            return view('dealerHome', compact('routes'));
        }
    }

    public function invoice()
    {
        return view('invoice');
    }

    public function searchAllSales(Request $request)
    {
        try {
            $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('dateFrom'))->startOfDay();
            $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('dateTo'))->endOfDay();
            $clients = Client::whereHas('Carts', function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('updated_at', [$dateFrom, $dateTo])
                      ->where('state', 1)
                      ->where('is_static', false);
            })->get();
            $products = ProductsCart::whereBetween('updated_at', [$dateFrom, $dateTo])->with('Cart', 'Product')->orderBy('updated_at', 'asc')->get();
            $abonos = AbonoClient::whereBetween('created_at', [$dateFrom, $dateTo])->with('Client', 'Abono')->orderBy('created_at', 'asc')->get();

            $data = [
                'clients' => []
            ];

            foreach ($clients as $client) {
                if ($client->Carts->count() === 0) {
                    continue;
                }
                $clientData = [
                    'name' => $client->name,
                    'products' => [],
                    'abonos' => []
                ];

                $clientProducts = $products->where('cart.client_id', $client->id);
                $clientAbonos = $abonos->where('client_id', $client->id);

                foreach ($clientAbonos as $abono) {
                    $abonoData = [
                        'id' => $abono->abono_id,
                        'name' => $abono->Abono->name,
                        'price' => $abono->Abono->price,
                        'date' => $abono->created_at->format('d/m/Y')
                    ];

                    $clientData['abonos'][] = $abonoData;
                }

                foreach ($clientProducts as $product) {
                    $productData = [
                        'id' => $product->Product->id,
                        'name' => $product->Product->name,
                        'quantity' => $product->quantity,
                        'price' => $product->setted_price,
                        'date' => $product->updated_at->format('d/m/Y')
                    ];

                    $clientData['products'][] = $productData;
                }

                $data['clients'][] = $clientData;
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al buscar las ventas',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function searchRoutes(Request $request) {
        try {
            $date = Carbon::createFromFormat('d/m/Y', $request->input('date'))->startOfDay();
            $routes = Route::where('is_static', false)
                ->whereDate('start_date', $date)
                ->with('Carts')
                ->with('Carts.ProductsCart')
                ->with('Carts.ProductsCart.Product')
                ->with('User')
                ->get();
            foreach ($routes as $route) {
                $route->info = $route->Info();
            }
            return response()->json([
                'success' => true,
                'data' => $routes
            ],
            201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al buscar las rutas',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
