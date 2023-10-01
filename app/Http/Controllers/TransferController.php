<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transfer\TransferRequest;
use App\Models\Client;
use App\Models\Route;
use App\Models\Transfer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transfers = Transfer::whereDate('created_at', today())->get();
        $users = User::where('rol_id', '!=', 1)->get();
        return view('transfers.index', compact('transfers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransferRequest $request)
    {
        try {
            $transfer = Transfer::create([
                'client_id' => $request->input('client_id'),
                'user_id' => $request->input('user_id'),
                'amount' => $request->input('amount'),
                'received_from' => $request->input('received_from'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transferencia creada correctamente',
                'data' => $transfer
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al crear la transferencia',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransferRequest $request)
    {
        $transfer = Transfer::find($request->input('transfer_id'));
        try {
            DB::beginTransaction();

            // Reponer la deuda al cliente
            $client = Client::find($transfer->client_id);
            $client->debt += $transfer->amount;
            $client->save();

            // Restar la nueva deuda al nuevo/mismo cliente
            $client = Client::find($request->input('client_id'));
            $client->debt -= $request->input('amount');
            $client->save();

            $transfer->update([
                'client_id' => $request->input('client_id'),
                'user_id' => $request->input('user_id'),
                'amount' => $request->input('amount'),
                'received_from' => $request->input('received_from'),
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();

            $response = [];
            $response[0]['id'] = $transfer->id;
            $response[0]['amount'] = $transfer->amount;
            $response[0]['client'] = [
                'name' => $transfer->Client->name,
                'id' => $transfer->Client->id
            ];
            $response[0]['user'] = [
                'name' => $transfer->User->name,
                'id' => $transfer->User->id
            ];
            $response[0]['created_at'] = $transfer->created_at;

            return response()->json([
                'success' => true,
                'message' => 'Transferencia editada correctamente',
                'data' => $transfer
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al editar la transferencia',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            $transfer = Transfer::find($request->input('id'));
            $transfer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transferencia eliminada correctamente',
                'data' => $transfer->id
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al eliminar la transferencia',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function searchTransfers(Request $request)
    {
        try {
            $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('dateFrom'))->startOfDay();
            $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('dateTo'))->endOfDay();
            $transfers = Transfer::whereBetween('created_at', [$dateFrom, $dateTo])->orderBy('created_at', 'desc')->get();

            $response = [];
            $i = 0;
            foreach ($transfers as $transfer) {
                $response[$i]['id'] = $transfer->id;
                $response[$i]['amount'] = $transfer->amount;
                $response[$i]['client'] = [
                    'name' => $transfer->Client->name,
                    'id' => $transfer->Client->id
                ];
                $response[$i]['user'] = [
                    'name' => $transfer->User->name,
                    'id' => $transfer->User->id
                ];
                $response[$i]['received_from'] = $transfer->received_from;
                $response[$i]['created_at'] = $transfer->created_at;
                $i++;
            }
            return response()->json([
                'success' => true,
                'data' => $response
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al buscar las transferencias',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function searchClients(Request $request)
    {
        try {
            $name = $request->input('name');
            $clients = Client::whereRaw('LOWER(name) LIKE ?', ["%".strtolower($name)."%"])->get();

            $response = [];
            $i = 0;
            foreach ($clients as $client) {
                $response[$i]['id'] = $client->id;
                $response[$i]['name'] = $client->name;
                $response[$i]['address'] = $client->adress;
                $response[$i]['debt'] = $client->debt;
                $response[$i]['debtOfMonth'] = $client->getDebtOfTheMonth();
                $response[$i]['debtOfPreviousMonth'] = $client->getDebtOfPreviousMonth();
                $response[$i]['dealers'] = $client->staticRoutesWithUserAndDayOfWeek();
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

    public function getTransfersRoute($id)
    {
        try {
            $route = Route::find($id);
            $transfers = Transfer::whereDate('created_at', '=', $route->start_date)
                ->where('user_id', $route->user_id)
                ->with(['Client' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->select('id', 'amount', 'client_id')
                ->get();
            $day = Carbon::parse($route->start_date)->format('d/m/Y');
            return response()->json([
                'success' => true,
                'message' => $transfers, $day,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al buscar transferencias',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
