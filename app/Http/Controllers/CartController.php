<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartCreateRequest;
use App\Http\Requests\Cart\CartUpdateRequest;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::all();
        return view('carts.index', compact('carts'));
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
    public function store(CartCreateRequest $request)
    {

        try {
            $productsJson = json_decode($request->input('products_array'));
            try {
                DB::beginTransaction();

                $cart = Cart::create([
                    'route_id' => $request->input('route_id'),
                    'client_id' => $request->input('client_id'),
                ]);
                $productsIds = [];
                foreach ($productsJson as $prod) {
                    $productsIds[] = $prod->product_id;
                }
                $products = Product::whereIn('id', $productsIds)->get();
                foreach ($products as $product) {
                    foreach ($productsJson as $prodJson) {
                        if ($prodJson->product_id === $product->id) {
                            $product->quantity = $prodJson->quantity;
                        }
                    }
                    ProductCart::create([
                        'product_id' => $product->id,
                        'cart_id' => $cart->id,
                        'quantity' => $product->quantity,
                        'setted_price' => $product->price,
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            return response()->json([
                'success' => true,
                'message' => 'Cart created successfully.',
                'data' => $cart
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cart creation failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartUpdateRequest $request)
    {
        $client = Cart::find($request->input('id'));
        try {
            $client->update([
                'client_id' => $request->input('client_id'),
                'delivered' => $request->input('delivered'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cart edited successfully.',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cart edition failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
