<?php

namespace App\Http\Controllers;

use App\Http\Requests\Route\ProductDispatchedUpdateRequest;
use App\Http\Requests\Route\ProductReturnedUpdateRequest;
use App\Http\Requests\Route\RouteCreateRequest;
use App\Http\Requests\Route\RouteUpdateRequest;
use App\Models\Abono;
use App\Models\AbonoClient;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductDispatched;
use App\Models\ProductsCart;
use App\Models\ProductsClient;
use App\Models\Route;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    private function getDate()
    {
        return Carbon::now(new DateTimeZone('America/Argentina/Buenos_Aires'));
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->rol_id == '1') {
            $routes = Route::where('day_of_week', date('N'))
                ->where('is_static', true)
                ->with(['Carts' => function($query) {
                    $query->orderBy('priority');
                }])
                ->get();
            return view('routes.adminIndex', compact('routes'));
        } else {
            $routes = Route::where('user_id', $user->id)
                ->where('is_static', true)
                ->get();
            return view('routes.index', compact('routes'));
        }
    }

    public function details($id)
    {
        $cash = PaymentMethod::where('method', 'Efectivo')->first();
        $payment_methods = PaymentMethod::all()->except($cash->id);

        $route = Route::with(['Carts.Client', 'Carts.CartPaymentMethod'])
            ->with(['Carts' => function ($query) {
                $query->orderBy('priority', 'asc');
            }])->find($id);


        if (auth()->user()->rol_id == '1')
        {
            $productsDispatched = ProductDispatched::where('route_id', $id)->with('Product')->get();

            $data = $this->getStats($route, $productsDispatched);

            return view('routes.details', compact('route', 'payment_methods', 'cash', 'productsDispatched', 'data'));
        } else {
            $clients = collect();
            foreach ($route->Carts as $cart) {
                $clients->push($cart->Client);
            }
            $clients = $clients->sortBy('name')->unique('id')->values();
            return view('routes.details', compact('route', 'payment_methods', 'cash', 'clients'));
        }
    }

    private function getStats($route, $productsDispatched)
    {
        $data = (object) [
            'day_collected' => 0,
            'day_expenses' => Expense::whereDate('created_at', $route->start_date)->where('user_id', $route->user_id)->get()->sum('spent'),
            'completed_carts' => 0,
            'pending_carts' => 0,
            'payment_used' => [],
            'products_returned' => [],
            'products_sold' => [],
            'in_deposit_routes' => 0,
        ];

        $data->products_sold = ProductsCart::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product:id,name,price')
            ->whereHas('cart', function ($query) use ($route) {
                $query->whereHas('route', function ($query) use ($route) {
                    $query->where('id', $route->id);
                });
            })
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
        ->get();

        foreach ($data->products_sold as &$product) {
            $product->total_returned = 0;
        }

        foreach ($route->ProductsReturned as $pr) {

            // Verificar si ya se agregó este producto a la colección "products_sold"
            $foundProduct = false;
            foreach ($data->products_sold as &$product) {
                if ($product->Product->id === $pr->Product->id) {
                    $product->total_returned = $pr->quantity;
                    $foundProduct = true;
                    break;
                }
            }

            // Si no se encontró, agregarlo a la colección "products_sold"
            if (!$foundProduct) {

                $productCart = new ProductsCart();
                $productCart->product()->associate($pr->Product);
                $productCart->total_sold = 0;
                $productCart->total_returned = $pr->quantity;

                $data->products_sold[] = $productCart;
            }
        }

        foreach ($data->products_sold as &$product) {
            $total_dispatched = $productsDispatched->where('product_id', $product->Product->id)->sum('quantity');

            $product->full_units = $total_dispatched != 0 ? ($total_dispatched - $product->total_sold) : 0;
            $product->empty_units = $product->total_returned + $product->total_sold;
        }

        foreach ($route->Carts as $cart) {
            // Calcular la cantidad de repartos completados
            if ($cart->state !== 0) {
                $data->completed_carts++;
            } else {
                $data->pending_carts++;
            }

            foreach ($cart->CartPaymentMethod as $pm) {
                $paymentMethodName = $pm->PaymentMethod->method;

                // Verificar si ya se agregó este método de pago al arreglo payment_used
                $foundPayment = false;
                foreach ($data->payment_used as &$payment) {
                    if ($payment['name'] === $paymentMethodName) {
                        $payment['total'] += $pm->amount;
                        $foundPayment = true;
                        break;
                    }
                }

                // Si no se encontró, agregarlo al arreglo payment_used
                if (!$foundPayment) {
                    $data->payment_used[] = [
                        'name' => $paymentMethodName,
                        'total' => $pm->amount,
                    ];
                }

                // Sumar al total de day_collected
                $data->day_collected += $pm->amount;
            }

        }


        if ($route->Carts()->count() === 0) {
            $data->in_deposit_routes++;
        }
        //dd($data->payment_used);
        return $data;
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
        $client = Client::find($request->input('client_id'));
        $abonoClient = null; // Inicializar la variable $abonoClient

        if ($client->abono_id !== null) {
            $abonoType = Abono::find($client->abono_id);
            $abonoType->client_id = $request->input('client_id');
            if ($client->abono_id !== "NULL") {
                $abonoClient = AbonoClient::where('abono_id', $abonoType->id)
                    ->where('client_id', $request->input('client_id'))
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->first();
            }
        } else {
            $abonoType = null;
        }
        return response()->json(['products' => $products,'abonoType' => $abonoType,'abonoClient' => $abonoClient, 'client_abono_id' => $client->abono_id]);
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
    public function getDealerRoutes(int $day, int $id)
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
        $clients = Client::select('id', 'name', 'adress as address', 'dni', 'phone')->orderBy('name')->get();
        foreach ($clients as $client) {
            $client->priority = $route->Carts()->where('client_id', $client->id)->value('priority') ?? null;
        }
        $clientsSelected = $clients->where('priority', '!=', null)->sortBy('priority')->sortBy(function($client) {
            return is_null($client->priority) ? 1 : 0;
        });
        $clients = $clients->where('priority', '==', null);
        $products = Product::all();
        return view('routes.cart', compact('route', 'clients', 'clientsSelected', 'products'));
    }

    public function newManualCart($id)
    {
        $route = Route::find($id);
        $clients = Client::all();
        $cash = PaymentMethod::where('method', 'Efectivo')->first();
        $payment_methods = PaymentMethod::all()->except($cash->id);
        return view('routes.manualCart', compact('route', 'clients', 'payment_methods', 'cash'));
    }

    public function createManualCart(Request $request)
    {
        try {
            $products_quantity = json_decode($request->input('products_quantity'), true);
            $payment_methods = json_decode($request->input('payment_methods'), true);
            $route_id = $request->input('route_id');
            $client_id = $request->input('client_id');
            $products_client = ProductsClient::where('client_id', $client_id)->get();
            $products = Product::all();

            DB::beginTransaction();
            $cart = Cart::create([
                'route_id' => $route_id,
                'client_id' => $client_id,
                'priority' => null,
                'state' => 1,
                'is_static' => false,
            ]);

            $products_cart = [];
            foreach ($products_quantity as $product) {
                $products_cart[] = [
                    'product_id' => $product["product_id"],
                    'cart_id' => $cart->id,
                    'quantity' => collect($products_quantity)->where('product_id', $product["product_id"])->first()['quantity'],
                    'setted_price' => $products->where('id', $product["product_id"])->value('price'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $client_stock = $products_client->where('product_id', $product['product_id'])->first()['stock'];
                if ($product['quantity'] > $client_stock) {
                    ProductsClient::where('client_id', $client_id)
                        ->where('product_id', $product['product_id'])
                        ->update(['stock' => ($product['quantity'])]);
                }
            }

            $total_paid = 0;
            $cart_payment_methods = [];
            foreach ($payment_methods as $payment) {
                $total_paid += $payment['amount'];
                $cart_payment_methods[] = [
                    'cart_id' => $cart->id,
                    'payment_method_id' => $payment['method'],
                    'amount' => $payment['amount'],
                ];
            }

            $total_cart = 0;
            foreach ($products_cart as $product) {
                $total_cart += $product['quantity'] * $product['setted_price'];
            }

            DB::table('cart_payment_methods')->insert($cart_payment_methods);
            DB::table('products_cart')->insert($products_cart);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta realizada correctamente',
                'data' => route('route.details', ['id' => $route_id])
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'title' => 'Error al crear la venta',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
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
                'start_date' => $this->getDate(),
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
        //
    }

    // For admin. Deletes and creates all static carts.
    public function updateClients(Request $request)
    {
        try {
            $route = Route::find($request->input('route_id'));
            $clientsJson = json_decode($request->input('clients_array'));

            DB::beginTransaction();
            $i = 0;
            foreach ($clientsJson as $client) {
                $i++;
                $carts[] = [
                    'route_id' => $route->id,
                    'client_id' => $client->id,
                    'priority' => $i,
                    'state' => null,
                    'is_static' => true,
                ];
            }

            Cart::where('route_id', $route->id)->where('is_static', true)->delete(); // Eliminar todos los carrtios del reparto
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
            $lastPriority = Cart::where('route_id', $route->id)->where('is_static', false)->max('priority');
            foreach ($clientsJson as $client) {
                $lastPriority++;
                $carts[] = [
                    'route_id' => $route->id,
                    'client_id' => $client->id,
                    'priority' => $lastPriority,
                    'state' => 0,
                    'is_static' => false,
                ];
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
            $route_id = $request->input('route_id');
            $products = Product::all();

            DB::beginTransaction();

            $productUpdates = [];
            foreach ($products as $product) {
                $productUpdates[] = [
                    'product_id' => $product->id,
                    'route_id' => $route_id,
                    'quantity' => collect($products_quantity)->where('product_id', $product->id)->first()['quantity'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ProductDispatched::where('route_id', $route_id)->delete();
            DB::table('products_dispatched')->insert($productUpdates);

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


    public function updateReturned(ProductReturnedUpdateRequest $request)
    {
        try {
            $products_quantity = json_decode($request->input('products_quantity'), true);
            $route_id = $request->input('route_id');
            $client_id = $request->input('client_id');

            $products_returned = [];
            foreach ($products_quantity as $product) {
                $products_returned[] = [
                    'product_id' => $product["product_id"],
                    'route_id' => $route_id,
                    'client_id' => $client_id,
                    'quantity' => collect($products_quantity)->where('product_id', $product["product_id"])->first()['quantity'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::beginTransaction();

            DB::table('products_returned')->insert($products_returned);

            foreach ($products_returned as $product) {
                ProductsClient::where('product_id', $product['product_id'])->where('client_id', $client_id)->update([
                    'stock' => DB::raw('stock - ' . $product['quantity'])
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Productos devueltos correctamente',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'title' => 'Error al devolver los productos',
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
                'message' => 'Reparto eliminado correctamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al eliminar el reparto',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
