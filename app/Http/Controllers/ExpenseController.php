<?php

namespace App\Http\Controllers;

use App\Http\Requests\Expense\ExpenseRequest;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $expenses = Expense::whereBetween('updated_at', [$request->input('dateFrom'), $request->input('dateTo')])->get();

            return response()->json([
                'success' => true,
                'data' => $expenses
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search expenses failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function employeeExpenses()
    {
        try {
            $expenses = Expense::where('user_id', auth()->User()->id)
                ->whereDate('updated_at', Carbon::today())
                ->get();

            return response()->json([
                'success' => true,
                'data' => $expenses
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search expenses failed: ' . $e->getMessage(),
            ], 400);
        }
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
}
