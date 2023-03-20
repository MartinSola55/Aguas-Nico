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
        Schema::create('ProductCart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ProductId')->constrained('Product');
            $table->foreignId('CartId')->constrained('Cart');
            $table->integer('Quantity');
            $table->integer('QuantitySent');
            $table->dateTime('CreatedAt');
            $table->dateTime('UpdatedAt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ProductCart');
    }
};
