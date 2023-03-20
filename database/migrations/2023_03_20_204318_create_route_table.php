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
        Schema::create('Route', function (Blueprint $table) {
            $table->id();
            $table->foreignId('UserId')->constrained('User');
            $table->string('DayOfWeek');
            $table->dateTime('StartTime');
            $table->dateTime('EndTime');
            $table->dateTime('CreatedAt');
            $table->dateTime('UpdatedAt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Route');
    }
};
