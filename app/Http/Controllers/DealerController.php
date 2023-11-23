<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartPaymentMethod;
use App\Models\Client;
use App\Models\ClientMachine;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductsCart;
use App\Models\ProductsClient;
use App\Models\Route;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DealerController extends Controller
{
    /**
     * Display a listing of the resource.
     */    
    public function index()
    {
        $users = User::where('rol_id', 2)->get();
        return view('dealers.index', compact('users'));
    }
    
    // General stats
    public function statistics()
    {
        // Total repartos
        $repartos_totales = Cart::with('Route')->whereHas('route', function ($query) {
            $query->where('is_static', false)
                ->whereYear('start_date', date('Y'))
                ->whereMonth('start_date', date('m'));
        })->where('is_static', false)->count();

        // Repartos completados
        $repartos_completados = Cart::with('Route')->whereHas('route', function ($query) {
            $query->where('is_static', false)
                ->whereYear('start_date', date('Y'))
                ->whereMonth('start_date', date('m'));
        })->where('state', 1)->where('is_static', false)->count();

        // Repartos pendientes
        $repartos_pendientes = Cart::with('Route')->whereHas('route', function ($query) {
            $query->where('is_static', false)
                ->whereYear('start_date', date('Y'))
                ->whereMonth('start_date', date('m'));
        })->where('state', '!=', 1)->where('is_static', false)->count();

        $repartos = [
            'totales' => $repartos_totales,
            'completados' => $repartos_completados,
            'pendientes' => $repartos_pendientes
        ];
        
        $stats = $this->generalStats();
        $anualSales = $this->anualSales(null);
        $monthlySales = $this->monthlySales(null);


        return view('stats', compact('repartos', 'stats', 'anualSales', 'monthlySales'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Total repartos
        $repartos_totales = Cart::with('Route')->whereHas('route', function ($query) use ($id) {
            $query->where('is_static', false)
                ->where('user_id', $id)
                ->whereYear('start_date', date('Y'))
                ->whereMonth('start_date', date('m'));
        })->where('is_static', false)->count();

        // Repartos completados
        $repartos_completados = Cart::with('Route')->whereHas('route', function ($query) use ($id) {
            $query->where('is_static', false)
                ->where('user_id', $id)
                ->whereYear('start_date', date('Y'))
                ->whereMonth('start_date', date('m'));
        })->where('state', 1)->where('is_static', false)->count();

        // Repartos pendientes
        $repartos_pendientes = Cart::with('Route')->whereHas('route', function ($query) use ($id) {
            $query->where('is_static', false)
                ->where('user_id', $id)
                ->whereYear('start_date', date('Y'))
                ->whereMonth('start_date', date('m'));
        })->where('state', '!=', 1)->where('is_static', false)->count();

        $repartos = [
            'totales' => $repartos_totales,
            'completados' => $repartos_completados,
            'pendientes' => $repartos_pendientes
        ];
        
        $stats = $this->stats($id);
        $anualSales = $this->anualSales($id);
        $monthlySales = $this->monthlySales($id);

        $dealer = User::find($id);
        $years = $this->getYears($id);

        return view('dealers.details', compact('dealer', 'repartos', 'stats', 'anualSales', 'monthlySales', 'years'));
    }

    private function anualSales($id)
    {
        $data = array_fill(0, 12, 0);

        // Total vendido
        $sales = ProductsCart::whereHas('cart', function ($query) use ($id) {
            $query->where('state', 1)
                ->where('is_static', false)
                ->whereHas('route', function ($query) use ($id) {
                    if ($id != null) {
                        $query->where('user_id', $id)
                              ->whereYear('start_date', date('Y'));
                    } else {
                        $query->whereYear('start_date', date('Y'));
                    }
                });
        })->get();

        foreach ($sales as $sale) {
            if ($sale->updated_at == null) continue;
            $mes = date('n', strtotime($sale->updated_at)) - 1;
            $data[$mes] += $sale->quantity * $sale->setted_price;
        };

        $graph = [];
        $i = 0;
        foreach ($data as $month) {
            $month_padded = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
            $graph[$i] = [
                'period' => '2023-' . $month_padded,
                'sold' => $month
            ];
            $i++;
        }
        
        return json_encode(['data' => $graph]);
    }
    
    private function monthlySales($id)
    {
        $month = date('m'); // obtener el número del mes actual
        $year = date('Y'); // obtener el año actual
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data = array_fill(0, $days_in_month, 0);

        // Total vendido
        $sales = ProductsCart::whereHas('cart', function ($query) use ($id) {
            $query->where('state', 1)
                  ->whereYear('created_at', date('Y'))
                  ->whereMonth('created_at', date('m'))
                  ->where('is_static', false)
                  ->whereHas('route', function ($query) use ($id) {
                    if ($id != null)
                        $query->where('user_id', $id);
                });
        })->get();

        foreach ($sales as $sale) {
            if ($sale->updated_at == null) continue;
            $dia = date('j', strtotime($sale->updated_at)) - 1;
            $data[$dia] += $sale->quantity * $sale->setted_price;
        };

        $graph = [];
        $i = 0;
        foreach ($data as $day) {
            $graph[$i] = [
                'period' => '2023-' . $month . '-' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'sold' => $day
            ];
            $i++;
        }
        
        return json_encode(['data' => $graph]);
    }

    private function stats($id)
    {
        // Producto mas vendido
        $product = Product::select('products.name', 'products.id')
            ->join('products_cart', 'products.id', '=', 'products_cart.product_id')
            ->whereIn('products_cart.cart_id', function ($query) use ($id) {
                $query->select('carts.id')
                    ->from('carts')
                    ->join('routes', 'carts.route_id', '=', 'routes.id')
                    ->where('routes.user_id', $id)
                    ->where('carts.state', 1)
                    ->where('carts.is_static', false);
            })
            ->orderBy('products_cart.quantity', 'desc')
            ->first();
        
        if ($product !== null) {
            // Total ventas del producto
            $product_sales = ProductsCart::where('product_id', $product->id)
            ->whereHas('cart', function ($query) use ($id) {
                $query->where('state', 1)
                    ->where('is_static', false)
                    ->whereHas('route', function ($query) use ($id) {
                        $query->where('user_id', $id)
                  ->whereYear('start_date', date('Y'));
                    });
            })
            ->sum('quantity');
    
            // Total vendido
            $totalSold = ProductsCart::whereHas('cart', function ($query) use ($id) {
                $query->where('state', 1)
                    ->where('is_static', false)
                    ->whereHas('route', function ($query) use ($id) {
                        $query->where('user_id', $id)
                  ->whereYear('start_date', date('Y'));
                    });
            })
            ->sum('quantity');
        } else {
            $product_sales = 0;
            $totalSold = 0;
        }

        $currentMonth = Carbon::now()->month;

        $totalCollected = CartPaymentMethod::whereHas('cart', function ($query) use ($id, $currentMonth) {
            $query->where('state', 1)
                ->where('is_static', false)
                ->whereHas('route', function ($query) use ($id, $currentMonth) {
                    $query->where('user_id', $id)
                        ->whereMonth('start_date', $currentMonth);
                });
        })
        ->sum('amount');

        $stats = [
            'product' => $product->name ?? 'Sin ventas',
            'product_sales' => $product_sales,
            'totalSold' => $totalSold,
            'totalCollected' => $totalCollected,
        ];
        return $stats;
    }


    private function generalStats()
    {
        // Producto mas vendido
        $product = Product::select('products.name', 'products.id')
            ->join('products_cart', 'products.id', '=', 'products_cart.product_id')
            ->whereIn('products_cart.cart_id', function ($query) {
                $query->select('carts.id')
                    ->from('carts')
                    ->join('routes', 'carts.route_id', '=', 'routes.id')
                    ->where('carts.state', 1)
                    ->where('carts.is_static', false);
            })
            ->orderBy('products_cart.quantity', 'desc')
            ->first();
        
        if ($product !== null) {
            // Total ventas del producto
            $product_sales = ProductsCart::where('product_id', $product->id)
            ->whereHas('cart', function ($query) {
                $query->where('state', 1)
                    ->where('is_static', false)
                    ->whereHas('route', function ($query) {
                        $query->whereYear('start_date', date('Y'));
                    });
            })
            ->sum('quantity');
    
            // Total vendido
            $totalSold = ProductsCart::whereHas('cart', function ($query) {
                $query->where('state', 1)
                    ->where('is_static', false)
                    ->whereHas('route', function ($query) {
                        $query->whereYear('start_date', date('Y'));
                    });
            })
            ->sum('quantity');
        } else {
            $product_sales = 0;
            $totalSold = 0;
        }

        $stats = [
            'product' => $product->name ?? 'Sin ventas',
            'product_sales' => $product_sales,
            'totalSold' => $totalSold
        ];
        return $stats;
    }

    private function getYears($id)
    {
        try {
            $routes = Route::where('user_id', $id)->where('is_static', false)->get();
            $years = $routes->map(function ($route) {
                return date('Y', strtotime($route->start_date));
            });
            $uniqueYears = $years->unique();
            return $uniqueYears;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getPendingCarts(Request $request)
    {
        try {
            $id = $request->input('id');
            $dateFrom = $request->input('dateFrom');
            $dateTo = $request->input('dateTo');
            $carts = Cart::where('state', '!=', 1)
                ->where('is_static', false)
                ->whereHas('route', function ($query) use ($id, $dateFrom, $dateTo) {
                    $query->where('user_id', $id)
                        ->whereBetween('start_date', [$dateFrom, $dateTo]);
                })
                ->with('Client')
                ->with('Route')
                ->orderBy('created_at')
                ->get();
            return response()->json([
                    'success' => true,
                    'data' => $carts
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al recuperar los repartos pendientes',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }
    public function searchClients(Request $request)
    {
        try {
            $id = $request->input('id');
            $day_of_week = $request->input('day_of_week');
            $clients = Route::where('user_id', $id)
                ->where('day_of_week', $day_of_week)
                ->where('is_static', true)
                ->with('Carts')
                ->with('Carts.Client')
                ->first();
            $clients->totalDebt = 0;
            foreach ($clients->Carts as $cart) {
                $clients->totalDebt += $cart->Client->debt;
            }

            return response()->json([
                'success' => true,
                'data' => $clients
            ],201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al recuperar los repartos pendientes',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function searchClientsMachines(Request $request)
    {
        try {
            $id = $request->input('id');
            $month = $request->input('month');
            $year = $request->input('year');

            if($month > date('m') && $year >= date('Y')) {
                return null;
            }

            $clientsIDs = [];
            $staticRoutes = Route::where('user_id', $id)
                ->where('is_static', true)
                ->with('Carts')
                ->get();
                
            foreach($staticRoutes as $route) {
                foreach ($route->Carts as $cart) {
                    $clientsIDs[] = $cart->client_id;
                }
            }

            $clientsWithMachines = Client::whereIn('id', $clientsIDs)
                ->where('machines', '!=', null)
                ->get();

                
            $clientesQueBajaron = ClientMachine::whereIn('client_id', $clientsWithMachines->pluck('id'))
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->pluck('client_id');
            $clientesQueNoBajaron = $clientsWithMachines->whereNotIn('id', $clientesQueBajaron);
            dd($clientesQueNoBajaron);
                
            return response()->json([
                'success' => true,
                'data' => $clientesQueNoBajaron
            ],201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al recuperar los clientes',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function searchProductsSold(Request $request)
    {
        try {
            $id = $request->input('id');
            $year = $request->input('year');
            $month = $request->input('month');

            if ($month > date('m') && $year >= date('Y')) {
                return null;
            }

            $productsCart = ProductsCart::whereHas('cart', function ($query) use ($id, $month, $year) {
                $query->where('state', 1)
                    ->where('is_static', false)
                    ->whereHas('route', function ($query) use ($id, $month, $year) {
                        $query->where('user_id', $id)
                            ->whereMonth('start_date', $month)
                            ->whereYear('start_date', $year);
                    });
            })
            ->with('Product')
            ->get();

            $products = $productsCart->groupBy(function ($item) {
                return $item->Product->bottle_type_id ?? $item->product_id;
            })->map(function ($group) {
                return [
                    'product' => $group->first()->Product->name,
                    'quantity' => $group->sum('quantity'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $products
            ],201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al recuperar los productos vendidos',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function searchClientsNotVisited(Request $request)
    {
        try {
            $id = $request->input('id');
            $dateFrom = $request->input('dateFrom');
            $dateTo = $request->input('dateTo');

            $clientsVisited = Client::whereHas('Carts', function ($query) use ($id, $dateFrom, $dateTo) {
                $query->where('state', 1)
                    ->where('is_static', false)
                    ->whereHas('route', function ($query) use ($id, $dateFrom, $dateTo) {
                        $query->where('user_id', $id)
                            ->whereBetween('start_date', [$dateFrom, $dateTo]);
                    });
            })->get();

            $clientsNotVisited = Client::whereHas('Carts', function ($query) use ($id, $dateFrom, $dateTo) {
                $query->where('state', '!=', 1)
                    ->where('is_static', false)
                    ->whereHas('route', function ($query) use ($id, $dateFrom, $dateTo) {
                        $query->where('user_id', $id)
                            ->whereBetween('start_date', [$dateFrom, $dateTo]);
                    });
            })->get();
            $clients = $clientsNotVisited->whereNotIn('id', $clientsVisited->pluck('id'));

            return response()->json([
                'success' => true,
                'data' => $clients
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al recuperar los repartos pendientes',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
