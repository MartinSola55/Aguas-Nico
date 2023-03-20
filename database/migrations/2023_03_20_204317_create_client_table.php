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
        Schema::create('Client', function (Blueprint $table) {
            $table->id();
            $table->string('Name');
            $table->string('Adress');
            $table->string('Phone');
            $table->string('Email');
            $table->double('Debt', 8, 2);
            $table->string('Dni');
            $table->boolean('Invoice');
            $table->text('Observation');
            $table->dateTime('CreatedAt');
            $table->dateTime('UpdatedAt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Client');
    }
};
