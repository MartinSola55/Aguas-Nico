<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientMachine;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MachineController extends Controller
{
    public function store(Request $request)
    {
        try {
            $client = Client::findOrFail($request->input('client_id'));
            $machine = Machine::findOrFail($client->machine_id);
            $available = $client->machines - ClientMachine::whereMonth('created_at', now()->month)
                ->where('machine_id', $machine->id)
                ->where('client_id', $client->id)
                ->sum('quantity');
            if ($available < $request->input('quantity')) {
                return response()->json([
                    'success' => false,
                    'title' => 'Error al renovar las máquinas',
                    'message' => 'Debes ingresar una cantidad menor o igual a la cantidad de máquinas disponibles',
                ], 400);
            }
            DB::beginTransaction();
            ClientMachine::create([
                'client_id' => $request->input('client_id'),
                'machine_id' => $client->machine_id,
                'quantity' => $request->input('quantity'),
                'price' => $machine->price,
            ]);

            $client->debt = $client->debt + ($request->input('quantity') * $machine->price);
            $client->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Máquina/s renovada/s correctamente',
                'data' => [$client->id, $available - $request->input('quantity')]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'title' => 'Error al renovar las máquinas',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
