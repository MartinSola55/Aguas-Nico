<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientMachine;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MachineController extends Controller
{

    public function index()
    {
        $machines = Machine::orderBy('id')->get();
        return view('machines.index', compact('machines'));
    }

    public function updatePrice(Request $request)
    {
        try {
            $machine = Machine::findOrFail($request->input('id'));
            $machine->price = $request->input('price');
            $machine->save();

            return response()->json([
                'success' => true,
                'message' => 'Precio actualizado correctamente',
                'data' => $machine,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar el precio',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function renew(Request $request)
    {
        try {
            $client = Client::findOrFail($request->input('client_id'));
            $machine = Machine::findOrFail($client->machine_id);

            DB::beginTransaction();
            ClientMachine::create([
                'client_id' => $client->id,
                'machine_id' => $client->machine_id,
                'quantity' => $client->machines,
                'price' => $machine->price,
            ]);

            $client->debt = $client->debt + ($client->machines * $machine->price);
            $client->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Máquina/s renovada/s correctamente',
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
