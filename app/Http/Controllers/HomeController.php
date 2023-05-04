<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
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
            $routes = Route::whereDate('start_date', today('America/Buenos_Aires'))
                ->with('Carts')
                ->with('Carts.ProductsCart')
                ->with('Carts.ProductsCart.Product')
                ->with('User')
                ->get();

            // Calcular las ganancias totales del día
            $data = (object) [
                'day_earnings' => 0,
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
                        $data->day_earnings += $pm->amount;
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
}
