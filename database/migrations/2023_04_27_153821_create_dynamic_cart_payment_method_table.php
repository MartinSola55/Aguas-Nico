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
        Schema::create('dynamic_cart_payment_method', function (Blueprint $table) {
            $table->foreignId('dynamic_cart_id')->constrained('dynamic_carts');
            $table->foreignId('payment_method_id')->constrained('payment_method');
            $table->double('amount', 8, 2);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_cart_payment_method');
    }
};
