<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartCreateRequest;
use App\Http\Requests\Cart\CartUpdateRequest;
use App\Http\Requests\Cart\ConfirmRequest;
use App\Models\Cart;
use App\Models\CartPaymentMethod;
use App\Models\Product;
use App\Models\ProductsCart;
use App\Models\ProductsClient;
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
        //
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
        //
    }

    public function changeState(Request $request)
    {
        try {
            $cart = Cart::find($request->input('id'));
            $cart->state = $request->input('state');
            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Reparto actualizado',
                'data' => $cart
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

    public function confirm(ConfirmRequest $request)
    {
        try {
            $products_quantity = json_decode($request->input('products_quantity'), true);
            $payment_methods = json_decode($request->input('payment_methods'), true);

            $productIds = collect($products_quantity)->pluck('product_id')->unique()->toArray();
            $prices = Product::whereIn('id', $productIds)->pluck('price', 'id');

            $client = Cart::find($request->input('cart_id'))->Client;
            $cart = Cart::find($request->input('cart_id'));
            $products_client = ProductsClient::where('client_id', $client->id)->get();

            $total_cart = 0;
            $total_paid = 0;

            DB::beginTransaction();

            $products_cart = [];
            foreach ($products_quantity as $product) {
                $total_cart += $product['quantity'] * $prices[$product['product_id']];
                $products_cart[] = [
                    'product_id' => $product['product_id'],
                    'cart_id' => $cart->id,
                    'quantity' => $product['quantity'],
                    'setted_price' => $prices[$product['product_id']],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $client_stock = $products_client->where('product_id', $product['product_id'])->first()['stock'];
                if ($product['quantity'] > $client_stock) {
                    ProductsClient::where('client_id', $client->id)
                        ->where('product_id', $product['product_id'])
                        ->update(['stock' => ($product['quantity'])]);
                }
            }

            $cart_payment_methods = [];
            foreach ($payment_methods as $payment) {
                $total_paid += $payment['amount'];
                $cart_payment_methods[] = [
                    'cart_id' => $cart->id,
                    'payment_method_id' => $payment['method'],
                    'amount' => $payment['amount'],
                ];
            }

            if ($total_paid < $total_cart) {
                $client->update(['debt' => $client->debt + $total_cart - $total_paid]);
            } else if ($total_paid >= $total_cart) {
                $client->update(['debt' => $client->debt + $total_cart - $total_paid]);
            }

            $cart->update(['state' => 1]);
            DB::table('cart_payment_methods')->insert($cart_payment_methods);
            DB::table('products_cart')->insert($products_cart);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reparto confirmado'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'title' => 'Error al confirmar el reparto',
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
            Cart::find($request->input('id'))->delete();

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
