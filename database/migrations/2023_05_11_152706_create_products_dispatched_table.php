<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products_dispatched', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products'); // ESTO DEBE SER NULLABLE (CAMBIO MANUAL)
            $table->foreignId('route_id')->constrained('routes');
            $table->integer('quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_dispatched');
    }
};
