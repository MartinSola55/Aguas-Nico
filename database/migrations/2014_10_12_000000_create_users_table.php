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
        Schema::create('User', function (Blueprint $table) {
            $table->id();
            $table->foreignId('RolId')->constrained('Rol');
            $table->string('Name');
            $table->string('Email')->unique();
            $table->string('Password');
            $table->string('TruckNumber');
            $table->rememberToken();
            $table->dateTime('CreatedAt');
            $table->dateTime('UpdatedAt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('User');
    }
};
