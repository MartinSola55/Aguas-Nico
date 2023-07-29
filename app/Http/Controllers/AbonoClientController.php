<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\AbonoClient;
use App\Models\AbonoLog;
use App\Models\BottleClient;
use App\Models\Product;
use App\Models\StockLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbonoClientController extends Controller
{
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
                'title' => 'Error al renovar el abono',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request)
    {
        try {
            $abonoClient = AbonoClient::find($request->input('abono_id'));
            $productModel = Product::find($abonoClient->abono->product_id);
            $cart_id = $request->input('cart_id');
            $bottleType = $productModel->bottle_type_id;

            DB::beginTransaction();
            if ($bottleType !== null) {
                BottleClient::firstOrCreate(['client_id' => $abonoClient->client_id,'bottle_types_id' => $bottleType])
                    ->increment('stock', $request->input('discount'));
            }
            $abonoClient->available -= $request->input('discount');
            $abonoClient->save();

            // Crear log de abono
            $abonoLog = AbonoLog::firstOrCreate(
                [
                    'cart_id' => $cart_id,
                    'abono_clients_id' => $abonoClient->id
                ],
                [
                    'cart_id' => $cart_id,
                    'abono_clients_id' => $abonoClient->id,
                    'quantity' => 0,
                    'created_at' => Carbon::now(),
                ]
            );

            $abonoLog->quantity += $request->input('discount');
            $abonoLog->updated_at = Carbon::now();
            $abonoLog->save();

            DB::commit();
            return response()->json([
                'success' => true,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar el descuento del abono',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function getLog(Request $request)
    {
        try {
            $log = AbonoLog::where('cart_id', $request->input('cart_id'))->first();

            if ($log) {
                $abonoType = AbonoClient::find($log->abono_clients_id);
                $log->name = $abonoType->Abono->name;
                $log->available = $log->Abonoclient->available + $log->quantity;

                return response()->json([
                    'success' => true,
                    'data' => $log,
                ], 201);
            } else {
                // No se encontró ningún registro
                return response()->json([
                    'success' => true,
                    'data' => null,
                ], 201);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al obtener cantidad de productos restantes del abono',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
