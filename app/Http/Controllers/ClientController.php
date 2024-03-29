<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ClientCreateRequest;
use App\Http\Requests\Client\ClientShowRequest;
use App\Http\Requests\Client\ClientUpdateInvoiceRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Http\Requests\Client\SearchSalesRequest;
use App\Models\Abono;
use App\Models\AbonoLog;
use App\Models\BottleClient;
use App\Models\Cart;
use App\Models\Client;
use App\Models\Machine;
use App\Models\Product;
use App\Models\ProductsCart;
use App\Models\ProductsClient;
use App\Models\StockLog;
use App\Models\Transfer;
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
        $clients = Client::where('is_active', true)
            ->with(['Products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        return view('clients.index', compact('clients'));
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
                // 'email' => $request->input('email'),
                'debt' => $request->input('debt'),
                // 'dni' => $request->input('dni'),
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
        $client->debtOfTheMonth = $client->getDebtOfTheMonth();
        $client->debtOfPreviousMonth = $client->getDebtOfPreviousMonth();
        $machines = Machine::all();

        $transfers = Transfer::where('client_id', $id)->limit(20)->get();
        $carts = Cart::where('client_id', $id)
            ->where('is_static', false)
            ->where('state', 1)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->with('ProductsCart', 'ProductsCart.Product', 'AbonoClient', 'AbonoClient.Abono', 'CartPaymentMethod', 'AbonoLog', 'AbonoLog.AbonoClient', 'AbonoLog.AbonoClient.Abono')
            ->get();

        $details = [];
        $i = -1;
        foreach ($carts as $cart) {
            $i++;
            $details[$i]["created_at"] = $cart->created_at;
            $details[$i]["detail"] = "";
            foreach ($cart->ProductsCart as $pc) {
                $details[$i]["detail"] .= $pc->Product->name . " x " . $pc->quantity . " - $" . $pc->quantity * $pc->setted_price . "<br>";
            }
            if ($cart->AbonoClient) {
                $details[$i]["detail"] .= $cart->AbonoClient->Abono->name . " - $" . $cart->AbonoClient->setted_price . "<br>";
            }
            if ($cart->AbonoLog && $cart->AbonoLog->quantity > 0) {
                $details[$i]["detail"] .= $cart->AbonoLog->AbonoClient->Abono->name . " - Bajó: " . $cart->AbonoLog->quantity . "<br>";
            }
            $details[$i]["total"] = $cart->CartPaymentMethod->sum('amount');
        }
        foreach ($transfers as $transfer) {
            $i++;
            $details[$i]["created_at"] = $transfer->created_at;
            $details[$i]["detail"] = "Transferencia";
            $details[$i]["total"] = $transfer->amount;
        }
        // Ordenar el array por fecha
        usort($details, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        $client_products = $this->getProducts($client);
        $abonos = Abono::orderBy('price')->where('is_active', true)->get();

        return view('clients.details', compact('client', 'client_products', 'abonos', 'details', 'machines'));
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
        $products = Product::orderBy('price')->where('is_active', true)->get();
        $productList = [];
        foreach ($products as $key => $product) {
            $productList[$key]['id'] = $product->id;
            $productList[$key]['name'] = $product->name;
            $productList[$key]['price'] = $product->price;

            $stock = ProductsClient::where('client_id', $client->id)->where('product_id', $product->id)->first()->stock ?? null;
            if ($stock != null) {
                if ($product->bottle_type_id !== null) {
                    $stock = BottleClient::where('client_id', $client->id)->where('bottle_types_id', $product->bottle_type_id)->first()->stock ?? null;
                    if ($stock != null) {
                        $productList[$key]['active'] = true;
                        $productList[$key]['stock'] = $stock;
                    }else {
                        $productList[$key]['active'] = false;
                        $productList[$key]['stock'] = null;
                    }
                }else {
                    $productList[$key]['active'] = true;
                    $productList[$key]['stock'] = $stock;
                }
            }else {
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
            $client_id = $request->input('client_id');
            DB::beginTransaction();
                foreach ($products_quantity as $product) {
                    $productClient = ProductsClient::where('client_id', $client_id)
                        ->where('product_id', $product["product_id"])
                        ->first();
                    if ($productClient) {
                        $productClient->update(['stock' => $product["quantity"]]);
                    }else {
                        ProductsClient::create([
                            'client_id' => $client_id,
                            'product_id' => $product["product_id"],
                            'stock' => $product["quantity"],
                        ]);
                    }
                    $modelProd = Product::find($product["product_id"]);
                    if ($modelProd->bottle_type_id !== null) {
                        $bottleClient = BottleClient::where('client_id', $client_id)
                            ->where('bottle_types_id', $modelProd->bottle_type_id)
                            ->first();
                        if ($bottleClient) {
                            $bottleClient->update(['stock' => $product["quantity"]]);
                        }else {
                            BottleClient::create([
                                'client_id' => $client_id,
                                'bottle_types_id' => $modelProd->bottle_type_id,
                                'stock' => $product["quantity"],
                            ]);
                        }
                    }
                }
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
            Client::find($client_id)->update(['abono_id' => $abono_id]);

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

    public function updateMachine(Request $request)
    {
        try {
            $client_id = $request->input('client_id');
            $machine_id = $request->input('machine_id');
            $quantity = $request->input('quantity');

            if($quantity == 0) {
                $machine_id = null;
            } else if($quantity < 0) {
                return response()->json([
                    'success' => false,
                    'title' => 'Error al actualizar la máquina',
                    'message' => 'La cantidad no puede ser menor a 0',
                ], 400);
            }
            Client::findOrFail($client_id)->update(['machine_id' => $machine_id, 'machines' => $quantity]);

            return response()->json([
                'success' => true,
                'message' => 'Máquina actualizada correctamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar la máquina',
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

    public function getHistory($id)
    {
        try {
            $carts = Cart::where('client_id', $id)
                ->where('is_static', false)
                ->where('state', 1)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->with('ProductsCart', 'ProductsCart.Product', 'AbonoClient', 'AbonoClient.Abono', 'CartPaymentMethod', 'AbonoLog', 'AbonoLog.AbonoClient', 'AbonoLog.AbonoClient.Abono')
                ->get();
            return response()->json([
                'success' => true,
                'message' => $carts,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al buscar historial de carritos',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function showInvoice($id) {
        $client = Client::find($id);

        return view('clients.invoice', compact('client'));
    }

    public function stockHistory (Request $request)
    {
        try {
            $history = StockLog::where('client_id', $request->input('client_id'))
                ->orderBy('updated_at', 'desc')
                ->get();

            $response = $history->map(function ($log) {
                $name = $log->product_id !== null ? $log->Product->name : ($log->bottle_types_id !== null ? $log->BottleType->name : null);

                $formattedDate = $log->updated_at->format('d/m/Y H:i');
                $action = $log->l_r == 0 ? 'Presta' : 'Devuelve';

                return [
                    'name' => $name,
                    'cant' => $log->quantity,
                    'action' => $action,
                    'date' => $formattedDate
                ];
            })->filter(); // Remove null values from the map

            return response()->json([
                'success' => true,
                'data' => $response->values(), // Reset array keys for consistency
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al buscar historial de stock',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }

    }
}
