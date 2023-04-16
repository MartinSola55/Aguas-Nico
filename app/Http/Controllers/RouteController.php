<?php

namespace App\Http\Controllers;

use App\Http\Requests\Route\RouteCreateRequest;
use App\Http\Requests\Route\RouteUpdateRequest;
use App\Models\Client;
use App\Models\Product;
use App\Models\Route;
use App\Models\User;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::all();
        return view('routes.index', compact('routes'));
    }
    
    public function new()
    {
        $users = User::all();
        return view('routes.new', compact('users'));
    }
    
    public function newCart($id)
    {
        $route = Route::find($id);
        $clients = Client::all();
        $products = Product::all();
        return view('routes.cart', compact('route', 'clients', 'products'));
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
    public function store(RouteCreateRequest $request)
    {
        try {
            $route = Route::create([
                'user_id' => $request->input('user_id'),
                'start_daytime' => $request->input('start_daytime'),
                'end_daytime' => $request->input('end_daytime'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Route created successfully.',
                'data' => $route
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Route creation failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RouteUpdateRequest $request)
    {
        $route = Route::find($request->input('id'));
        try {
            $route->update([
                'user_id' => $request->input('user_id'),
                'start_daytime' => $request->input('start_daytime'),
                'end_daytime' => $request->input('end_daytime'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Route edited successfully.',
                'data' => $route
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Route edition failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        //
    }
}
