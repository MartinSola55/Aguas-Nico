<?php

namespace App\Http\Controllers;

use App\Http\Requests\Route\RouteCreateRequest;
use App\Http\Requests\Route\RouteUpdateRequest;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\Route;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->rol_id == '1') {
            $routes = $this->getRoutesByDate(today()->toDateString());
            return view('routes.index', compact('routes'));
        } else {
            $routes = $this->getDealerRoutes(today()->toDateString(), $user->id);
            return view('routes.index', compact('routes'));
        }
    }

    public function details($id)
    {
        $route = Route::find($id);
        return view('routes.details', compact('route'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $routes = $this->getRoutesByDate($request->input('start_daytime'))->load(['Carts', 'User']);
        foreach ($routes as $route) {
                $route->info = $route->Info();
        }
        return response()->json(['routes' => $routes]);
    }

    /*
        Get all the products from a specific cart (when opening the modal)
    */
    public function getProductCarts(Request $request)
    {
        $cart = Cart::where('id', $request->input('id'))->with('ProductsCart.Product')->first();
        return response()->json(['cart' => $cart]);
    }

    /**
     * Get routes by date.
     *
     * @param  string  $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRoutesByDate(string $date)
    {
        return Route::whereDate('start_daytime', $date)->get();
    }

    /**
     * Get routes by date and user_id.
     *
     * @param  string  $date
     * @param  int  $user_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getDealerRoutes(string $date, int $id)
    {
        return Route::where('user_id', $id)
            ->whereDate('start_daytime', $date)
            ->get();
    }

    public function new()
    {
        $users = User::where('rol_id', '!=', 1)->get();
        return view('routes.new', compact('users'));
    }

    public function newCart($id)
    {
        $route = Route::find($id);
        $clients = Client::all();
        $products = Product::all();
        return view('routes.cart', compact('route', 'clients', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RouteCreateRequest $request)
    {
        try {
            $route = Route::create([
                'user_id' => $request->input('user_id'),
                'start_daytime' => $request->input('start_daytime'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Route created successfully.',
                'data' => $route->id
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Route creation failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RouteUpdateRequest $request)
    {
        $route = Route::find($request->input('id'));
        try {
            $route->update([
                'user_id' => $request->input('user_id'),
                'start_daytime' => $request->input('start_daytime'),
                'end_daytime' => $request->input('end_daytime'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Route edited successfully.',
                'data' => $route
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Route edition failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        //
    }
}
