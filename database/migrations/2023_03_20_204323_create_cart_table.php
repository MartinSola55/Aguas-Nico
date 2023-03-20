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
        Schema::create('Cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('RouteId')->constrained('Route');
            $table->foreignId('ClientId')->constrained('Client');
            $table->boolean('Delivered');
            $table->dateTime('StartDate');
            $table->dateTime('EndDate');
            $table->dateTime('CreatedAt');
            $table->dateTime('UpdatedAt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Cart');
    }
};
