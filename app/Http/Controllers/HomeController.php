<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expense;
use App\Models\ProductsCart;
use App\Models\Route;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                'day_expenses' => Expense::whereDate('created_at', $this->getDate())->get()->sum('spent'),
                'completed_routes' => 0,
                'pending_routes' => 0,
                'in_deposit_routes' => 0,
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

            return view('home', compact('routes', 'data'));
        } // Repartidor
        else {
            $routes = Route::where('user_id', $user->id)
                ->where('is_static', true)
                ->get();
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

            $data = [
                'clients' => []
            ];

            foreach ($clients as $client) {
                if ($client->Carts->count() === 0) {
                    continue;
                }
                $clientData = [
                    'name' => $client->name,
                    'products' => []
                ];

                $clientProducts = $products->where('cart.client_id', $client->id);



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
}
