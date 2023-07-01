<?php

namespace App\Http\Controllers;

use App\Http\Requests\Expense\ExpenseRequest;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('name', 'id')->where('rol_id', '!=', '1')->orderBy('name', 'asc')->get();
        return view('expenses.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        try {
            $expense = Expense::create([
                'user_id' => $request->input('user_id'),
                'spent' => $request->input('spent'),
                'description' => $request->input('description'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gasto creado correctamente',
                'data' => $expense
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al crear el gasto',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request)
    {
        $expense = Expense::find($request->input('id'));
        try {
            $expense->update([
                'spent' => $request->input('spent'),
                'description' => $request->input('description'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gasto actualizado correctamente',
                'data' => $expense
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al actualizar el gasto',
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
            $expense = Expense::find($request->input('id'));
            $expense->delete();

            return response()->json([
                'success' => true,
                'message' => 'Gasto eliminado correctamente',
                'data' => $expense
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al eliminar el gasto',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function searchExpenses(Request $request)
    {
        try {
            $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('dateFrom'))->startOfDay();
            $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('dateTo'))->endOfDay();
            $user = auth()->user();
            if ($user->rol_id == '1') {
                $expenses = Expense::whereBetween('created_at', [$dateFrom, $dateTo])->orderBy('created_at', 'desc')->get();
            } else {
                $expenses = Expense::whereBetween('created_at', [$dateFrom, $dateTo])->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
            }
            $response = [];
            $i = 0;
            foreach ($expenses as $expense) {
                $response[$i]['id'] = $expense->id;
                $response[$i]['description'] = $expense->description;
                $response[$i]['spent'] = $expense->spent;
                $response[$i]['user'] = $expense->User->name;
                $response[$i]['date'] = $expense->created_at->format('d/m/Y');
                $i++;
            }

            return response()->json([
                'success' => true,
                'data' => $response
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al buscar los gastos',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
