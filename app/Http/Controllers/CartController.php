<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartCreateRequest;
use App\Http\Requests\Cart\CartUpdateRequest;
use App\Http\Requests\Cart\ConfirmRequest;
use App\Models\BottleClient;
use App\Models\Cart;
use App\Models\CartPaymentMethod;
use App\Models\Product;
use App\Models\ProductsCart;
use App\Models\ProductsClient;
use App\Models\StockLog;
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
            $cash = $request->input('cash');
            $renew_abono = json_decode($request->input('renew_abono'), true);

            $productIds = collect($products_quantity)->pluck('product_id')->unique()->toArray();
            $prices = Product::whereIn('id', $productIds)->pluck('price', 'id');

            $client = Cart::find($request->input('cart_id'))->Client;
            $cart = Cart::find($request->input('cart_id'));

            if ($renew_abono > 0) {
                $total_cart = $renew_abono;
            } else {
                $total_cart = 0;
            }

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
                $productId = $product['product_id'];

                // Obtener el modelo del producto correspondiente al product_id
                $productModel = Product::find($productId);

                $bottleType = $productModel->bottle_type_id;
                StockLog::create([
                    'client_id' => $client->id,
                    'cart_id' => $cart->id,
                    'bottle_type_id' => $bottleType,
                    'product_id' => $bottleType === null ? $productId : null,
                    'quantity' => $product['quantity'],
                    'l_r' => 0,          //si es 0=l, si es 1=r
                ]);
                if ($bottleType !== null) {
                    BottleClient::firstOrCreate(['client_id' => $client->id,'bottle_types_id' => $bottleType])
                        ->increment('stock', $product['quantity']);
                } else {
                    ProductsClient::firstOrCreate(['client_id' => $client->id, 'product_id' => $product['product_id']])
                        ->increment('stock', $product['quantity']);
                }
            }

            // Agregar metodo de pago en efectivo
            CartPaymentMethod::create([
                'cart_id' => $cart->id,
                'payment_method_id' => 1, // ESTA HARCODED PARA EFECTIVO
                'amount' => $cash,
            ]);

            $client->increment(['debt' => $total_cart - $total_paid]);

            $cart->update(['state' => 1, 'take_debt' => $total_cart - $total_paid]);
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

    public function returnStock(Request $request)
    {
        try {
            $client_id = Cart::find($request->input('cart_id'))->Client->id;
            if ($request->input('log_id') === 'null') {
                if ($request->input('product') === 'false') {
                    DB::beginTransaction();
                        $responseId = StockLog::create([
                            'client_id' => $client_id,
                            'cart_id' => $request->input('cart_id'),
                            'product_id' => NULL,
                            'bottle_types_id' => $request->input('type_id'),
                            'quantity' => $request->input('quantity'),
                            'l_r' => 1,
                        ]);
                    DB::commit();
                } elseif ($request->input('product') === 'true') {
                    DB::beginTransaction();
                        $responseId = StockLog::create([
                            'client_id' => $client_id,
                            'cart_id' => $request->input('cart_id'),
                            'product_id' => $request->input('type_id'),
                            'bottle_types_id' => NULL,
                            'quantity' => $request->input('quantity'),
                            'l_r' => 1,
                        ]);
                    DB::commit();
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Devolución exitosa',
                    'data' => $responseId
                ], 201);
            } else {
                DB::beginTransaction();
                    StockLog::where('id', $request->input('log_id'))->update([
                        'quantity' => $request->input('quantity'),
                    ]);
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Devolución exitosa',
                    'data' => null
                ], 201);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'title' => 'Error al cargar devolución de productos',
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
