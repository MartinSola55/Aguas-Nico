<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\Product;
use App\Models\ProductsCart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
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
    public function store(ProductCreateRequest $request)
    {
        try {
            $product = Product::create([
                'name' => $request->input('name'),
                'stock' => $request->input('stock'),
                'price' => $request->input('price'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Producto creado correctamente',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al crear el producto',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
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


    public function stats($id)
    {
        $product = Product::find($id);
        $products = ProductsCart::select('quantity', 'setted_price', 'updated_at')
            ->where('product_id', $id)
            ->get();

        $graph = array_fill(0, 12, 0);
        $total_earnings = 0;

        foreach ($products as $prod) {
            $total_earnings += $prod->quantity * $prod->setted_price;
            $mes = date('n', strtotime($prod->updated_at)) - 1;
            $graph[$mes] += $prod->quantity;
        };

        return view('products.stats', compact('product','graph','total_earnings'))->with('graph', json_encode($graph));
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
    public function update(ProductUpdateRequest $request)
    {
        $product = Product::find($request->input('id'));
        try {
            $product->update([
                'name' => $request->input('name'),
                'stock' => $request->input('stock'),
                'price' => $request->input('price'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Producto editado correctamente',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al editar el producto',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
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
