<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\AbonoClient;
use App\Models\BottleClient;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;

class AbonoClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $abonoType = Abono::find($request->input('abono_id'));
            $abonoClient = AbonoClient::create([
                    'client_id' => $request->input('client_id'),
                    'abono_id' => $request->input('abono_id'),
                    'cart_id' => $request->input('cart_id'),
                    'setted_price' => $abonoType->price,
                    'available' => $abonoType->quantity,
            ]);

            return response()->json([
                'success' => true,
                'data' => ['abonoType' => $abonoType,'abonoClient' => $abonoClient]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al renovar abono',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $abonoClient = AbonoClient::find($request->input('abono_id'));
            $productModel = Product::find($abonoClient->abono->product_id);
            $bottleType = $productModel->bottle_type_id;
            if ($bottleType !== null) {
                StockLog::create([
                    'client_id' => $abonoClient->client_id,
                    'cart_id' => $abonoClient->cart_id,
                    'bottle_type_id' => $bottleType,
                    'quantity' => $request->input('discount'),
                    'l_r' => 0,          //si es 0=l, si es 1=r
                ]);
                BottleClient::firstOrCreate(['client_id' => $abonoClient->client_id,'bottle_types_id' => $bottleType])
                    ->increment('stock', $request->input('discount'));
            }
            $abonoClient->available -= $request->input('discount');
            $abonoClient->save();
            return response()->json([
                'success' => true,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar descuento de Abono',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}