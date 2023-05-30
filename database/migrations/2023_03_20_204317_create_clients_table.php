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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('adress');
            $table->string('phone');
            $table->string('email');
            $table->double('debt', 8, 2)->default(0);
            $table->string('dni')->nullable();
            $table->boolean('invoice')->default(0);
            $table->boolean('is_active')->default(1);
            $table->text('observation')->nullable();
            $table->string('invoice_type')->nullable();
            $table->string('business_name')->nullable();
            $table->string('tax_condition')->nullable();
            $table->string('cuit')->nullable();
            $table->string('tax_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
