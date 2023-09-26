<?php

namespace App\Http\Controllers;

use App\Models\AbonoClient;
use App\Models\Client;
use App\Models\ProductsCart;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generateInvoice($id)
    {
        $route = Route::findOrFail($id)->load('Carts', 'Carts.Client');
        return view('invoice', compact('route'));
    }

    public function searchAllSales(Request $request)
    {
        try {
            $dateFrom = Carbon::createFromFormat('Y-m-d', $request->input('dateFrom'))->startOfDay();
            $dateTo = Carbon::createFromFormat('Y-m-d', $request->input('dateTo'))->endOfDay();
            $route = Route::findOrFail($request->input('route_id'));
            $clients = Client::whereHas('Carts', function ($query) use ($dateFrom, $dateTo, $route) {
                $query->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->where('state', 1)
                    ->where('is_static', false)
                    ->whereHas('Route', function ($query) use ($route) {
                        $query->where('id', $route->id);
                    });
            })
            ->where('invoice', true)->orderBy('name', 'asc')->get();
            $products = ProductsCart::whereBetween('created_at', [$dateFrom, $dateTo])->with('Cart', 'Product')->orderBy('created_at', 'asc')->get();
            $abonos = AbonoClient::whereBetween('created_at', [$dateFrom, $dateTo])->with('Client', 'Abono')->orderBy('created_at', 'asc')->get();

            $data = [
                'clients' => []
            ];

            foreach ($clients as $client) {
                if ($client->Carts->count() === 0 && $client->Abonos->count() === 0) {
                    continue;
                }
                $clientData = [
                    'name' => $client->name,
                    'cuit' => $client->cuit,
                    'invoice_type' =>$client->invoice_type,
                    'products' => [],
                    'abonos' => []
                ];

                $clientProducts = $products->where('cart.client_id', $client->id);
                $clientAbonos = $abonos->where('client_id', $client->id);

                foreach ($clientAbonos as $abono) {
                    $abonoData = [
                        'id' => $abono->abono_id,
                        'name' => $abono->Abono->name,
                        'price' => $abono->setted_price,
                        'date' => $abono->created_at->format('d/m/Y')
                    ];

                    $clientData['abonos'][] = $abonoData;
                }

                foreach ($clientProducts as $product) {
                    $productData = [
                        'id' => $product->Product->id,
                        'name' => $product->Product->name,
                        'quantity' => $product->quantity,
                        'price' => $product->setted_price,
                        'date' => $product->created_at->format('d/m/Y')
                    ];

                    $clientData['products'][] = $productData;
                }

                $data['clients'][] = $clientData;
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'title' => 'Error al buscar las ventas',
                'message' => 'Intente nuevamente o comuníquese para soporte',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
