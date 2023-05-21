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
        Schema::create('cart_payment_methods', function (Blueprint $table) {
            $table->foreignId('cart_id')->constrained('carts');
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->double('amount', 8, 2);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_payment_methods');
    }
};
