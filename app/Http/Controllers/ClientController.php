<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ClientCreateRequest;
use App\Http\Requests\Client\ClientShowRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Http\Requests\Client\SearchSalesRequest;
use App\Models\Cart;
use App\Models\Client;
use App\Models\ProductCart;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
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
    public function store(ClientCreateRequest $request)
    {
        try {
            $client = Client::create([
                'name' => $request->input('name'),
                'adress' => $request->input('adress'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'debt' => $request->input('debt'),
                'dni' => $request->input('dni'),
                'invoice' => $request->input('invoice') == 1 ? true : false,
                'observation' => $request->input('observation'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client created successfully.',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client creation failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Client::find($id);
        $cartIds = Cart::where('client_id', $id)->pluck('id');

        $products = ProductCart::whereIn('cart_id', $cartIds)
                    ->whereNotNull('quantity_sent')
                    ->get();

        $auxGraph = [];
        foreach ($products as $product) {
            if (isset($auxGraph[$product->product_id])) {
                $auxGraph[$product->product_id]['data'] += $product->quantity_sent;
            } else {
                $auxGraph[$product->product_id]['label'] = $product->Product->name;
                $auxGraph[$product->product_id]['data'] = $product->quantity_sent;
                $auxGraph[$product->product_id]['color'] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            }
        }

        // Reindex array
        $graph = array_values($auxGraph);

        return view('clients.details', compact('client','graph'));
    }

    public function searchSales(SearchSalesRequest $request){
        try {
            $cartIds = Cart::where('client_id', $request->input('client_id'))->pluck('id');
            $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('dateFrom'))->startOfDay();
            $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('dateTo'))->endOfDay();
            $products = ProductCart::whereIn('cart_id', $cartIds)
                        ->whereNotNull('quantity_sent')
                        ->whereBetween('updated_at', [$dateFrom, $dateTo])
                        ->get();
            $response = [];
            foreach ($products as $product) {
                if (isset($response[$product->product_id])) {
                    $response[$product->product_id]['quantity'] += $product->quantity_sent;
                }else {
                    $response[$product->product_id]['name'] = $product->Product->name;
                    $response[$product->product_id]['quantity'] = $product->quantity_sent;
                    $response[$product->product_id]['price'] = $product->setted_price;
                }
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

    public function show_invoice($id)
    {
        $client = Client::find($id);
        return view('clients.invoice', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientUpdateRequest $request)
    {
        $client = Client::find($request->input('id'));
        try {
            $client->update([
                'name' => $request->input('name'),
                'adress' => $request->input('adress'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'debt' => $request->input('debt'),
                'dni' => $request->input('dni'),
                'invoice' => $request->input('invoice') == 1 ? true : false,
                'observation' => $request->input('observation'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Client edited successfully.',
                'data' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client edition failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
