<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartCreateRequest;
use App\Http\Requests\Cart\CartUpdateRequest;
use App\Http\Requests\Cart\ConfirmRequest;
use App\Models\AbonoClient;
use App\Models\AbonoLog;
use App\Models\BottleClient;
use App\Models\Cart;
use App\Models\CartPaymentMethod;
use App\Models\Client;
use App\Models\DebtPaymentLog;
use App\Models\Product;
use App\Models\ProductsCart;
use App\Models\ProductsClient;
use App\Models\StockLog;
use Carbon\Carbon;
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


    public function edit(Request $request)
    {
        try {
            $cart = Cart::find($request->input('cart_id'));
            $products_quantity = json_decode($request->input('products_quantity'), true);
            $cash = $request->input('cash');
            
            DB::beginTransaction();
            if ($request->input('abono_log_id_edit') != "null") {
                $abonoLog = AbonoLog::find($request->input('abono_log_id_edit'));
                if ($abonoLog) {
                    $edit_available = AbonoClient::where('id', $abonoLog->abono_clients_id)->latest()->first();

                    if ($request->input('abono_log_quantity_new_edit') > $request->input('abono_log_quantity_available_edit')) {
                        return response()->json([
                            'success' => false,
                            'title' => 'Error al actualizar el reparto',
                            'message' => 'La cantidad de abono a utilizar es mayor a la disponible',
                        ], 400);
                    }
                    if ($edit_available) {
                        $edit_available->update([
                            'available' => $request->input('abono_log_quantity_available_edit') - $request->input('abono_log_quantity_new_edit')
                        ]);

                        $abonoLog->update([
                            'quantity' => $request->input('abono_log_quantity_new_edit')
                        ]);
                    }

                    $stockLog = StockLog::firstOrCreate(
                        [
                            'product_id' => $abonoLog->product_id,
                            'client_id' => $cart->client_id,
                            'l_r' => 0,
                            'cart_id' => $cart->id,
                        ],
                        [
                            'product_id' => $abonoLog->product_id,
                            'client_id' => $cart->client_id,
                            'l_r' => 0,
                            'cart_id' => $cart->id,
                            'created_at' => Carbon::now(),
                        ]
                    );
                    $stockLog->quantity = $request->input('abono_log_quantity_new_edit');
                    $stockLog->updated_at = Carbon::now();
                    $stockLog->save();
                }
            }


            $total_cart = 0;
            foreach ($cart->ProductsCart as $pc) {
                foreach ($products_quantity as $product) {
                    if ($pc->product_id == $product['product_id']) {
                        $pc->quantity = $product['quantity'];
                        $pc->save();

                        $total_cart += $product['quantity'] * $pc->setted_price;
                    }
                }
            }

            foreach ($cart->StockLogs->where('l_r', 0) as $sl) {
                foreach ($products_quantity as $product) {
                    if ($sl->product_id == $product['product_id'] && $sl->client_id == $cart->client_id) {
                        $sl->quantity = $product['quantity'];
                        $sl->save();
                    }
                }
            }

            // Actualizar metodo de pago en efectivo
            CartPaymentMethod::where('cart_id', $cart->id)->where('payment_method_id', 1)->update(['amount' => $cash]);

            $cart->update(['state' => 1, 'take_debt' => $total_cart - $cash]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Bajada actualizada',
                'data' => $cart
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar el reparto',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
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
            $cash = $request->input('cash') ?? 0;
            $renew_abono = json_decode($request->input('renew_abono'), true);

            $productIds = collect($products_quantity)->pluck('product_id')->unique()->toArray();
            $prices = Product::whereIn('id', $productIds)->pluck('price', 'id');

            $client = Cart::find($request->input('cart_id'))->Client;
            $cart = Cart::find($request->input('cart_id'))->load('Client');

            if ($renew_abono > 0) {
                $total_cart = $renew_abono;
            } else {
                $total_cart = 0;
            }

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

                $bottleType = $productModel->bottle_type_id ?? null;
                StockLog::create([
                    'client_id' => $client->id,
                    'cart_id' => $cart->id,
                    'bottle_types_id' => $bottleType,
                    'product_id' => $bottleType === null ? $productId : null,
                    'quantity' => $product['quantity'],
                    'l_r' => 0,          //si es 0=l, si es 1=r
                ]);
                if ($bottleType !== null) {
                    BottleClient::firstOrCreate(['client_id' => $client->id, 'bottle_types_id' => $bottleType])
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

            $client->increment('debt', $total_cart - $cash);

            // DebtPaymentLog::create([
            //     'client_id' => $client->id,
            //     'cart_id' => $cart->id,
            //     'debt' => $cart->ProductsCart()->sum('quantity', '*', 'setted_price')
            // ]);

            // Tiene que ir SI O SI primero la creacion de productos y despues la actualizacion del carrito
            DB::table('products_cart')->insert($products_cart);
            $cart->update(['state' => 1, 'take_debt' => $total_cart - $cash]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reparto confirmado',
                'data' => $cart
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
            $cart_id = $request->input('cart_id');
            $client_id = Cart::find($cart_id)->Client->id;
            $type_id = $request->input('type_id');

            DB::beginTransaction();
            if ($request->input('product') === 'false') {
                $log = StockLog::firstOrCreate(
                    [
                        'client_id' => $client_id,
                        'cart_id' => $cart_id,
                        'bottle_types_id' => $type_id,
                        'l_r' => 1,
                    ],
                    [
                        'client_id' => $client_id,
                        'cart_id' => $cart_id,
                        'bottle_types_id' => $type_id,
                        'l_r' => 1,
                    ]
                );
            } else {
                $log = StockLog::firstOrCreate(
                    [
                        'client_id' => $client_id,
                        'cart_id' => $cart_id,
                        'product_id' => $type_id,
                        'l_r' => 1,
                    ],
                    [
                        'l_r' => 1,
                        'client_id' => $client_id,
                        'cart_id' => $cart_id,
                        'product_id' => $type_id,
                    ]
                );
            }
            $log->quantity = $request->input('quantity');
            $log->updated_at = Carbon::now();
            $log->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Devolución exitosa',
                'data' => $log
            ], 201);
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

    public function searchClients(Request $request)
    {
        try {
            $name = $request->input('name');
            $clients = Client::whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($name) . "%"])->get();

            $response = [];
            $i = 0;
            foreach ($clients as $client) {
                $response[$i]['id'] = $client->id;
                $response[$i]['name'] = $client->name;
                $response[$i]['address'] = $client->adress;
                $i++;
            }
            return response()->json([
                'success' => true,
                'data' => $response
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al buscar los clientes',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
