<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;

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
        $routes = Route::whereDate('start_daytime', now()->toDateString())->get();

        // Calcular las ganancias totales del día
        $day_earnings = 0;
        $completed_routes = 0;
        $in_deposit_routes = 0;
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
                    $completed_routes++;
                } else if ($counter === $total_carts && $completed_carts === 0) {
                    $in_deposit_routes++;
                }
                
                foreach ($cart->ProductsCart as $product_cart) {
                    $day_earnings += $product_cart->Product->price * $product_cart->quantity_sent;
                }
            }
            if ($route->Carts()->count() === 0) {
                $in_deposit_routes++;
            }
        }
        $pending_routes = $routes->count() - $completed_routes - $in_deposit_routes;


        return view('home', compact('routes', 'day_earnings', 'completed_routes', 'pending_routes', 'in_deposit_routes'));
    }
}
