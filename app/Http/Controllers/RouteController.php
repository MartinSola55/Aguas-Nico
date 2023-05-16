<?php

namespace App\Http\Controllers;

use App\Http\Requests\Route\ProductDispatchedUpdateRequest;
use App\Http\Requests\Route\RouteCreateRequest;
use App\Http\Requests\Route\RouteUpdateRequest;
use App\Models\Cart;
use App\Models\Client;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductDispatched;
use App\Models\ProductsClient;
use App\Models\Route;
use App\Models\User;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->rol_id == '1') {
            $routes = $this->getRoutesByDate(date('N'));
            return view('routes.adminIndex', compact('routes'));
        } else {
            $routes = $this->getDealerRoutes(date('N'), $user->id);
            return view('routes.index', compact('routes'));
        }
    }

    public function details($id)
    {
        $cash = PaymentMethod::where('method', 'Efectivo')->first();
        $payment_methods = PaymentMethod::all()->except($cash->id);
        $route = Route::with(['Carts' => function ($query) {
            $query->orderBy('priority', 'asc');
        }])->find($id);
        $productsDispatched = ProductDispatched::where('route_id', $id)->with('Product')->get();
        return view('routes.details', compact('route', 'payment_methods', 'cash', 'productsDispatched'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        if ($user->rol_id == '1') {
            $routes = $this->getRoutesByDate($request->input('day_of_week'))->load(['Carts', 'User']);
            foreach ($routes as $route) {
                $route->info = $route->Info();
            }
            return response()->json(['routes' => $routes]);
        } else {
            $routes = $this->getDealerRoutes($request->input('day_of_week'), $user->id)->load(['Carts', 'User']);
            foreach ($routes as $route) {
                $route->info = $route->Info();
            }
            return response()->json(['routes' => $routes]);
        }
    }

    /*
        Get all the products from a specific cart (when opening the modal)
    */
    public function getProductsClient(Request $request)
    {
        $products = ProductsClient::where('client_id', $request->input('client_id'))->with('Product')->get();
        return response()->json(['products' => $products]);
    }

    /**
     * Get routes by date.
     *
     * @param  int  $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRoutesByDate(int $day)
    {
        return Route::where('day_of_week', $day)
            ->where('is_static', true)
            ->with(['Carts' => function($query) {
                $query->orderBy('priority');
            }])
            ->get();
    }

    /**
     * Get routes by date and user_id.
     *
     * @param  int  $day
     * @param  int  $user_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getDealerRoutes(int $day, int $id)
    {
        $route = Route::where('user_id', $id)
        ->limit(10)
        ->orderBy('start_date', 'desc')
        ->where('is_static', false)
        ->where('day_of_week', $day)
        ->with(['Carts' => function($query) {
            $query->orderBy('priority');
        }])
        ->get();

        return $route;
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
        foreach ($clients as $client) {
            $client->priority = $route->Carts()->where('client_id', $client->id)->value('priority') ?? null;
        }
        $products = Product::all();
        return view('routes.cart', compact('route', 'clients', 'products'));
    }

    /**
     * Reparto dinámico.
     */
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $static_route = Route::where('id', $request->input('id'))->with('Carts')->first();
            $static_carts = $static_route->Carts;

            $newCarts = [];
            $newRoute = Route::create([
                'user_id' => $static_route->user_id,
                'day_of_week' => $static_route->day_of_week,
                'start_date' => today(),
                'end_date' => null,
                'is_static' => false,
            ]);
            foreach ($static_carts as $cart) {
                $newCarts[] = [
                    'route_id' => $newRoute->id,
                    'client_id' => $cart->client_id,
                    'priority' => $cart->priority,
                    'state' => 0,
                    'is_static' => false,
                ];
            }
            DB::table('carts')->insert($newCarts);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reparto creado correctamente',
                'data' => route('route.details', ['id' => $newRoute->id])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'title' => 'Error al crear el reparto',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Reparto estático
     */
    public function store(RouteCreateRequest $request)
    {
        try {
            $route = Route::create([
                'user_id' => $request->input('user_id'),
                'day_of_week' => $request->input('day_of_week'),
                'is_static' => true,
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
                'message' => 'Repato actualizado correctamente',
                'data' => $route
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar el reparto',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    // For admin. Deletes and creates all static carts.
    public function updateClients(Request $request)
    {
        try {
            $route = Route::find($request->input('route_id'));
            $clientsJson = json_decode($request->input('clients_array'));

            DB::beginTransaction();

            foreach ($clientsJson as $client) {
                if ($client->priority !== 0) {
                    $carts[] = [
                        'route_id' => $route->id,
                        'client_id' => $client->id,
                        'priority' => $client->priority,
                        'state' => null,
                        'is_static' => true,
                    ];
                }
            }

            Cart::where('route_id', $route->id)->delete(); // Eliminar todos los carrtios del reparto
            DB::table('carts')->insert($carts); // Insertar los nuevos carritos al reparto
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Clientes actualizados correctamente',
                'data' => $route
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar los clientes',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    // For employee. Adds a new cart.
    public function addClients(Request $request)
    {
        try {
            $route = Route::find($request->input('route_id'));
            $clientsJson = json_decode($request->input('clients_array'));

            foreach ($clientsJson as $client) {
                if ($client->priority !== 0) {
                    $carts[] = [
                        'route_id' => $route->id,
                        'client_id' => $client->id,
                        'priority' => $client->priority,
                        'state' => 0,
                        'is_static' => false,
                    ];
                }
            }

            DB::beginTransaction();
            DB::table('carts')->insert($carts); // Insertar los nuevos carritos al reparto
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Clientes agregados correctamente',
                'data' => $route
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'title' => 'Error al agregar los clientes',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function updateDispatched(ProductDispatchedUpdateRequest $request)
    {
        try {
            $products_quantity = json_decode($request->input('products_quantity'), true);
            $productIds = collect($products_quantity)->pluck('product_id')->unique()->toArray();
            $route_id = $request->input('route_id');
            $products_dispatched = ProductDispatched::whereIn('product_id', $productIds)->where('route_id', $route_id)->get();

            DB::beginTransaction();

            $productUpdates = [];
            foreach ($products_dispatched as $product) {
                $productUpdates[] = [
                    'id' => $product->id,
                    'product_id' => $product->product_id,
                    'route_id' => $route_id,
                    'quantity' => collect($products_quantity)->where('product_id', $product->product_id)->first()['quantity'],
                    'updated_at' => now(),
                ];
            }
            DB::table('products_dispatched')->upsert($productUpdates, 'id', ['quantity', 'updated_at']);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Productos despachados actualizados correctamente',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar los productos despachados',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        try {
            Route::find($request->input('id'))->delete();

            return response()->json([
                'success' => true,
                'message' => 'Route deleted successfully.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Route deletion failed: ' . $e->getMessage(),
            ], 400);
        }
    }
}
