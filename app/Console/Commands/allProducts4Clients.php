<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductsClient;
use Illuminate\Console\Command;

class allProducts4Clients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:all-products4-clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea los registros ProductsClient con stock cero para los productos que no tiene el cliente.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $clients = Client::all();

        $products = Product::all();

        foreach ($clients as $client) {
            foreach ($products as $product) {
                $existingProductClient = ProductsClient::where('client_id', $client->id)
                                                    ->where('product_id', $product->id)
                                                    ->first();

                if (!$existingProductClient) {
                    ProductsClient::create([
                        'client_id' => $client->id,
                        'product_id' => $product->id,
                        'stock' => 0
                    ]);
                }
            }
        }
        $this->info('Se han creado los registros ProductsClient para todos los Clients correctamente.');
    }
}
