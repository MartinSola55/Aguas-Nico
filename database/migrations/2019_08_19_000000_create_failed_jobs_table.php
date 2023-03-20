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
        Schema::create('FailedJob', function (Blueprint $table) {
            $table->id();
            $table->string('Uuid')->unique();
            $table->text('Connection');
            $table->text('Queue');
            $table->longText('Payload');
            $table->longText('Exception');
            $table->timestamp('FailedAt')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FailedJob');
    }
};
