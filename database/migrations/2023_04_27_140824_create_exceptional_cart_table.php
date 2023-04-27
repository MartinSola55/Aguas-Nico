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
        Schema::create('exceptional_cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journey_id')->constrained('journey');
            $table->foreignId('client_id')->constrained('clients');
            $table->integer('priority');
            $table->integer('state')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exceptional_cart');
    }
};
