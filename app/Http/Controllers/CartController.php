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

                    // Restablecer y actualizar stock del cliente
                    if ($abonoLog->AbonoClient->Abono->Product->bottle_type_id != null) {
                        $bottleClient = BottleClient::where('client_id', $cart->client_id)->where('bottle_types_id', $abonoLog->AbonoClient->Abono->Product->bottle_type_id)->first();
                        $bottleClient->decrement('stock', $abonoLog->quantity);
                        $bottleClient->increment('stock', $request->input('abono_log_quantity_new_edit'));
                    } else {
                        $productClient = ProductsClient::where('client_id', $cart->client_id)->where('product_id', $abonoLog->AbonoClient->Abono->Product->id)->first();
                        $productClient->decrement('stock', $abonoLog->quantity);
                        $productClient->increment('stock', $request->input('abono_log_quantity_new_edit'));
                    }

                    // Actualizar abono disponible
                    if ($edit_available) {
                        $edit_available->update([
                            'available' => $request->input('abono_log_quantity_available_edit') - $request->input('abono_log_quantity_new_edit')
                        ]);

                        $abonoLog->update([
                            'quantity' => $request->input('abono_log_quantity_new_edit')
                        ]);
                    }
                }

                // Actualizar los StockLogs de los productos
                foreach ($products_quantity as $pc) {
                    $product = Product::find($pc["product_id"]);
                    if ($product->bottle_type_id == null) {
                        $stockLog = StockLog::firstOrCreate(
                            [
                                'product_id' => $product->id,
                                'client_id' => $cart->client_id,
                                'l_r' => 0,
                                'cart_id' => $cart->id,
                            ],
                            [
                                'product_id' => $product->id,
                                'client_id' => $cart->client_id,
                                'l_r' => 0,
                                'cart_id' => $cart->id,
                                'created_at' => Carbon::now(),
                            ]
                        );
                    } else {
                        $stockLog = StockLog::firstOrCreate(
                            [
                                'bottle_types_id' => $product->bottle_type_id,
                                'client_id' => $cart->client_id,
                                'l_r' => 0,
                                'cart_id' => $cart->id,
                            ],
                            [
                                'bottle_types_id' => $product->bottle_type_id,
                                'client_id' => $cart->client_id,
                                'l_r' => 0,
                                'cart_id' => $cart->id,
                                'created_at' => Carbon::now(),
                            ]
                        );
                    }

                    $stockLog->quantity = $pc["quantity"];
                    $stockLog->updated_at = Carbon::now();
                    $stockLog->save();  
                }
            }

            $total_cart = 0;
            foreach ($cart->ProductsCart as $pc) {
                // Restablecer stock del cliente
                if ($pc->Product->bottle_type_id != null) {
                    $bottleClient = BottleClient::where('client_id', $cart->client_id)->where('bottle_types_id', $pc->Product->bottle_type_id)->first();
                    $bottleClient->decrement('stock', $pc->quantity);
                } else {
                    $productClient = ProductsClient::where('client_id', $cart->client_id)->where('product_id', $pc->product_id)->first();
                    $productClient->decrement('stock', $pc->quantity);
                }
                // Acá se resta de la deuda el precio de los productos que se sacan
                $cart->Client->decrement('debt', $pc->quantity * $pc->setted_price);

                foreach ($products_quantity as $product) {
                    if ($pc->product_id == $product['product_id']) {
                        $pc->quantity = $product['quantity'];
                        $pc->save();

                        $total_cart += $product['quantity'] * $pc->setted_price;

                        // Actualizar stock del cliente con la nueva cantidad
                        if ($pc->Product->bottle_type_id != null) {
                            $bottleClient = BottleClient::where('client_id', $cart->client_id)->where('bottle_types_id', $pc->Product->bottle_type_id)->first();
                            $bottleClient->increment('stock', $product['quantity']);
                        } else {
                            $productClient = ProductsClient::where('client_id', $cart->client_id)->where('product_id', $pc->product_id)->first();
                            $productClient->increment('stock', $product['quantity']);
                        }
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
            $client = $cart->Client;

            // Actualizar metodo de pago en efectivo
            $cartPM = CartPaymentMethod::where('cart_id', $cart->id)->where('payment_method_id', 1)->first();
            $client->increment('debt', $cartPM->amount);
            CartPaymentMethod::where('cart_id', $cart->id)->where('payment_method_id', 1)->update(['amount' => $cash]);
            $client->decrement('debt', $cash);
            
            // Acá se suma a la deuda el precio de las nuevas cantidades de productos, y más arriba se restan las anteriores
            $client->increment('debt', $total_cart);

            $abonoPrice = AbonoClient::where('cart_id', $cart->id)->first();
            if ($abonoPrice) {
                $cart->update(['state' => 1, 'take_debt' => $total_cart - $cash + $abonoPrice->setted_price]);
            } else {
                $cart->update(['state' => 1, 'take_debt' => $total_cart - $cash]);
            }

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

            $productIds = collect($products_quantity)->pluck('product_id')->unique()->toArray();
            $prices = Product::whereIn('id', $productIds)->pluck('price', 'id');

            $client = Cart::find($request->input('cart_id'))->Client;
            $cart = Cart::find($request->input('cart_id'))->load('Client');

            if ($cart->state != 0) {
                return response()->json([
                    'success' => false,
                    'title' => 'Error al confirmar el reparto',
                    'message' => 'El reparto ya fue confirmado',
                    'text' => 'Probablemente presionó el botón de confirmar dos veces. Intente recargar la página'
                ], 400);
            }
            
            $total_cart = 0;
            DB::beginTransaction();

            if ($request->input('abono_id') && $request->input('discount')) {
                $abonoController = new AbonoClientController();
                // Llama al método update() en la instancia del controlador
                $resultado = $abonoController->update($request->input('discount'), $request->input('cart_id'), $request->input('abono_id'));
                if ($resultado->getStatusCode() != 201) {
                    return $resultado;
                }
            }

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

                // Actualizar stock de los productos del cliente
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
            $abonoPrice = AbonoClient::where('cart_id', $cart->id)->first();
            
            // Tiene que ir SI O SI primero la creacion de productos y despues la actualizacion del carrito
            DB::table('products_cart')->insert($products_cart);
            if ($abonoPrice) {
                $cart->update(['state' => 1, 'take_debt' => $total_cart - $cash + $abonoPrice->setted_price]);
            } else {
                $cart->update(['state' => 1, 'take_debt' => $total_cart - $cash]);
            }
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
            $client = Client::find($client_id);

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
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );

                // Sumar el stock que ya tenia si es que existe, y luego restar la cantidad que se devuelve
                if ($log->quantity !== null) {
                    $client->BottleClient()->where('bottle_types_id', $type_id)->increment('stock', $log->quantity);
                }
                $client->BottleClient()->where('bottle_types_id', $type_id)->decrement('stock', $request->input('quantity'));

                // Ademas de la bottle, para que en client index figure bien la cantidad, se resta la cantidad de ProductsClient
                foreach ($client->ProductsClient()->get() as $pc) {
                    if ($pc->product->bottle_type_id == $type_id) {
                        $pc->decrement('stock', $request->input('quantity'));
                        if ($log->quantity !== null) {
                            $pc->increment('stock', $log->quantity);
                        }
                    }
                }
                
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
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );
                if ($log->quantity !== null) {
                    $client->ProductsClient()->where('product_id', $type_id)->increment('stock', $log->quantity);
                }
                
                $client->ProductsClient()->where('product_id', $type_id)->decrement('stock', $request->input('quantity'));
            }
            $log->quantity = $request->input('quantity');
            $log->updated_at = Carbon::now();
            $log->save();
            $client->save();

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
            DB::beginTransaction();
            Cart::find($request->input('id'))->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Reparto eliminado correctamente',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
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
