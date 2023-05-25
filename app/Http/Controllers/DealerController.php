<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartPaymentMethod;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductsCart;
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
                  ->whereYear('start_date', date('Y'));
        })->where('is_static', false)->count();

        // Repartos completados
        $repartos_completados = Cart::with('Route')->whereHas('route', function ($query) {
            $query->where('is_static', false)
                  ->whereYear('start_date', date('Y'));
        })->where('state', 1)->where('is_static', false)->count();

        // Repartos pendientes
        $repartos_pendientes = Cart::with('Route')->whereHas('route', function ($query) {
            $query->where('is_static', false)
                  ->whereYear('start_date', date('Y'));
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
                  ->whereYear('start_date', date('Y'));
        })->where('is_static', false)->count();

        // Repartos completados
        $repartos_completados = Cart::with('Route')->whereHas('route', function ($query) use ($id) {
            $query->where('is_static', false)
                  ->where('user_id', $id)
                  ->whereYear('start_date', date('Y'));
        })->where('state', 1)->where('is_static', false)->count();

        // Repartos pendientes
        $repartos_pendientes = Cart::with('Route')->whereHas('route', function ($query) use ($id) {
            $query->where('is_static', false)
                  ->where('user_id', $id)
                  ->whereYear('start_date', date('Y'));
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

        return view('dealers.details', compact('dealer', 'repartos', 'stats', 'anualSales', 'monthlySales'));
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
}
