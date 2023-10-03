<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\AbonoClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbonoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $abonos = Abono::orderBy('price')->get();
        return view('abonos.index', compact('abonos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function abonoClients()
    {
        $abonos = AbonoClient::whereDate('created_at', '>=', date('Y-m-01'))->get()->load('Client');
        return view('abonos.clientes', compact('abonos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $abono = AbonoClient::find($request->input('abono_id'))->load('Client', 'Abono');
        try {
            if ($request->input('available') > $abono->Abono->quantity || $request->input('available') < 0) {
                return response()->json([
                    'success' => false,
                    'title' => 'Error al editar el abono',
                    'message' => 'El abono no puede ser mayor al disponible',
                ], 400);
            }
            $abono->update([
                'available' => $request->input('available'),
                'updated_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Abono editado correctamente',
                'data' => $abono
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al editar el abono',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function updatePrice(Request $request)
    {
        $abono = Abono::find($request->input('abono_id'));
        try {
            if ($abono == null) {
                return response()->json([
                    'success' => false,
                    'title' => 'Error al editar el abono',
                    'message' => 'Intente nuevamente o comuníquese para soporte'
                ], 400);
            }
            $abono->update([
                'price' => $request->input('price'),
                'updated_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Abono editado correctamente',
                'data' => $abono
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al editar el abono',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function getClients($id)
    {
        $clients = AbonoClient::where('abono_id', $id)->with('Client')->get();

        $responseData = $clients->map(function ($client) {
            return [
                'id' => $client->Client->id,
                'name' => $client->Client->name,
                'address' => $client->Client->adress,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $responseData
        ], 201);
    }
}
