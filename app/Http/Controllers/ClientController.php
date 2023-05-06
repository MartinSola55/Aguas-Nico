<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ClientCreateRequest;
use App\Http\Requests\Client\ClientShowRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Http\Requests\Client\SearchSalesRequest;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductsCart;
use App\Models\ProductsClient;
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
        $clients = Client::all();
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
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client created successfully.',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client creation failed: ' . $e->getMessage(),
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

        return view('clients.details', compact('client', 'graph', 'client_products'));
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
                'message' => 'Search sales failed: ' . $e->getMessage(),
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
        $client = Client::find($request->input('id'));
        try {
            $client->update([
                'name' => $request->input('name'),
                'adress' => $request->input('adress'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'debt' => $request->input('debt'),
                'dni' => $request->input('dni'),
                'invoice' => $request->input('invoice') == 1 ? true : false,
                'observation' => $request->input('observation'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client edited successfully.',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client edition failed: ' . $e->getMessage(),
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
            } else {
                $productList[$key]['active'] = false;
            }
        }
        return $productList;
    }

    public function updateProducts(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputValues = $request->input(); // Obtener todos los valores de los inputs del formulario
            $client_id = $request->input('client_id'); // Obtener el cliente
            
            $products = [];
            foreach ($inputValues as $key => $value) {
                if (strpos($key, 'product_') === 0) { // Verificar si el input corresponde a un producto
                    $productId = substr($key, strlen('product_')); // Obtener el id del producto del nombre del input
                    $products[] = [
                        'client_id' => $client_id,
                        'product_id' => $productId
                    ];
                }
            }

            ProductsClient::where('client_id', $client_id)->delete(); // Eliminar todos los productos del cliente
            DB::table('products_client')->insert($products); // Insertar los nuevos productos del cliente
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Products edited successfully.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Products edition failed: ' . $e->getMessage(),
            ], 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
