<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartCreateRequest;
use App\Http\Requests\Cart\CartUpdateRequest;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartCreateRequest $request)
    {
        try {
            $cart = Cart::create([
                'route_id' => $request->input('route_id'),
                'client_id' => $request->input('client_id'),
                'delivered' => $request->input('delivered'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cart created successfully.',
                'data' => $cart
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cart creation failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartUpdateRequest $request)
    {
        $client = Cart::find($request->input('id'));
        try {
            $client->update([
                'client_id' => $request->input('client_id'),
                'delivered' => $request->input('delivered'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cart edited successfully.',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cart edition failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
