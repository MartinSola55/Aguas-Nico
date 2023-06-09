<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ClientCreateRequest;
use App\Http\Requests\Client\ClientShowRequest;
use App\Http\Requests\Client\ClientUpdateInvoiceRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Http\Requests\Client\SearchSalesRequest;
use App\Models\Abono;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductsCart;
use App\Models\ProductsClient;
use App\Models\StockLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all()->load('ProductsClient');
        // dd($clients);
        return view('clients.index', compact('clients'));
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
    public function store(ClientCreateRequest $request)
    {
        try {
            $client = Client::create([
                'name' => $request->input('name'),
                'adress' => $request->input('adress'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'debt' => $request->input('debt'),
                'dni' => $request->input('dni'),
                'invoice' => $request->input('invoice') == 1 ? true : false,
                'observation' => $request->input('observation'),
                'invoice_type' => $request->input('invoice_type'),
                'business_name' => $request->input('business_name'),
                'tax_condition' => $request->input('tax_condition'),
                'cuit' => $request->input('cuit'),
                'tax_address' => $request->input('tax_address')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cliente creado correctamente',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al crear el cliente',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Client::find($id);
        $cartIds = Cart::where('client_id', $id)
            ->where('is_static', false)
            ->where('state', '!=', 0)
            ->pluck('id');

        $products = ProductsCart::whereIn('cart_id', $cartIds)->get();

        $auxGraph = [];
        foreach ($products as $product) {
            if (isset($auxGraph[$product->product_id])) {
                $auxGraph[$product->product_id]['data'] += $product->quantity;
            } else {
                $auxGraph[$product->product_id]['label'] = $product->Product->name;
                $auxGraph[$product->product_id]['data'] = $product->quantity;
                $auxGraph[$product->product_id]['color'] = '#' . substr(md5(Str::slug($product->Product->name)), 0, 6);
            }
        }

        // Reindex array
        $graph = array_values($auxGraph);

        $client_products = $this->getProducts($client);

        $abonos = Abono::all();

        return view('clients.details', compact('client', 'graph', 'client_products', 'abonos'));
    }

    public function searchSales(SearchSalesRequest $request){
        try {
            $cartIds = Cart::where('client_id', $request->input('client_id'))->pluck('id');
            $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('dateFrom'))->startOfDay();
            $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('dateTo'))->endOfDay();
            $products = ProductsCart::whereIn('cart_id', $cartIds)
                        ->whereNotNull('quantity')
                        ->whereBetween('updated_at', [$dateFrom, $dateTo])
                        ->get();
            $response = [];
            foreach ($products as $product) {
                if (isset($response[$product->product_id])) {
                    $response[$product->product_id]['quantity'] += $product->quantity;
                }else {
                    $response[$product->product_id]['name'] = $product->Product->name;
                    $response[$product->product_id]['quantity'] = $product->quantity;
                    $response[$product->product_id]['price'] = $product->setted_price;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $response
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

    public function show_invoice($id)
    {
        $client = Client::find($id);
        return view('clients.invoice', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientUpdateRequest $request)
    {
        try {
            $client = Client::findOrFail($request->input('id'));
            $client->update([
                'name' => $request->input('name'),
                'adress' => $request->input('adress'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'debt' => $request->input('debt'),
                'dni' => $request->input('dni'),
                'invoice' => $request->input('invoice') == 1 ? true : false,
                'observation' => $request->input('observation')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cliente editado correctamente',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al editar el cliente',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function updateInvoiceData(ClientUpdateInvoiceRequest $request)
    {
        try {
            $client = Client::findOrFail($request->input('id'));
            $client->update([
                'invoice_type' => $request->input('invoice_type'),
                'business_name' => $request->input('business_name'),
                'tax_condition' => $request->input('tax_condition'),
                'cuit' => $request->input('cuit'),
                'tax_address' => $request->input('tax_address')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Datos de facturación editados correctamente',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al editar los datos de facturación',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function getProducts(Client $client) {
        $products = Product::all();
        $productList = [];
        foreach ($products as $key => $product) {
            $productList[$key]['id'] = $product->id;
            $productList[$key]['name'] = $product->name;
            $client_products = $client->Products;

            $exists = $client_products->contains('id',$product->id);

            if ($exists) {
                $productList[$key]['active'] = true;
                $productList[$key]['stock'] = ProductsClient::where('client_id', $client->id)->where('product_id', $product->id)->first()->stock;
            } else {
                $productList[$key]['active'] = false;
                $productList[$key]['stock'] = null;
            }
        }
        return $productList;
    }

    public function getStock(Client $client, Request $request)
    {
        $list = [];
        try {
            $stockLog = StockLog::where('cart_id', $request->input('cart_id'))
                ->where('l_r', 1)
                ->get();

            foreach ($client->ProductsClient as $product) {
                if ($product->Product->bottle_type_id === null) {
                    $existingLog = $stockLog->where('product_id', $product->product_id)->first();

                    $list['products'][] = [
                        'id' => $product->product_id,
                        'name' => $product->Product->name,
                        'stock' => $existingLog ? $existingLog->quantity : 0,
                        'log_id' => $existingLog ? $existingLog->id : null, // Asignar log_id como el ID del log existente o null
                    ];
                }
            }

            foreach ($client->BottleClient as $bottle) {
                $existingLog = $stockLog->where('bottle_types_id', $bottle->bottle_types_id)->first();

                $list['bottle'][] = [
                    'id' => $bottle->bottle_types_id,
                    'name' => $bottle->BottleType->name,
                    'stock' => $existingLog ? $existingLog->quantity : 0,
                    'log_id' => $existingLog ? $existingLog->id : null, // Asignar log_id como el ID del log existente o null
                ];
            }

            $list['cart_id'] = $request->input('cart_id');

            return response()->json([
                'success' => true,
                'data' => $list,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar el estado del cliente',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function updateProducts(Request $request)
    {
        try {
            $products_quantity = json_decode($request->input('products_quantity'), true);
            $client_id = $request->input('client_id'); // Obtener el cliente
            $productsUpdated = [];

            DB::beginTransaction();

            foreach ($products_quantity as $product) {
                $productsUpdated[] = [
                    'client_id' => $client_id,
                    'product_id' => $product["product_id"],
                    'stock' => $product["quantity"],
                ];
            }

            ProductsClient::where('client_id', $client_id)->delete(); // Eliminar todos los productos del cliente
            DB::table('products_client')->insert($productsUpdated); // Insertar los nuevos productos del cliente
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Productos actualizados correctamente',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar los productos',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function updateAbono(Request $request)
    {
        try {
            $client_id = $request->input('client_id');
            $abono_id = $request->input('abono_id');
            DB::beginTransaction();
                Client::find($client_id)->update(['abono_id' => $abono_id]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Abono actualizado correctamente',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar el abono',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function setIsActive(Request $request)
    {
        try {
            $client = Client::find($request->input("id"));
            $client = $client->update([
                'is_active' => !$client->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estado del cliente actualizado correctamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar el estado del cliente',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
