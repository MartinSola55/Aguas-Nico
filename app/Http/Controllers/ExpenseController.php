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
        $users = User::select('name', 'id')->where('rol_id', '!=', '1')->get();
        return view('expenses.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
                'message' => 'Expense created successfully.',
                'data' => $expense
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Expense creation failed: ' . $e->getMessage(),
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
                'message' => 'Expense edited successfully.',
                'data' => $expense
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Expense edition failed: ' . $e->getMessage(),
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

    public function searchExpenses(Request $request)
    {
        try {
            $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('dateFrom'))->startOfDay();
            $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('dateTo'))->endOfDay();
            $user = auth()->user();
            if ($user->rol_id == '1') {
                $expenses = Expense::whereBetween('created_at', [$dateFrom, $dateTo])->get();
            } else {
                $expenses = Expense::whereBetween('created_at', [$dateFrom, $dateTo])->where('user_id', $user->id)->get();
            }
            $response = [];
            $i = 0;
            foreach ($expenses as $expense) {
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
                'message' => 'Search sales failed: ' . $e->getMessage(),
            ], 400);
        }
    }
}
