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
        Schema::create('PersonalAccessToken', function (Blueprint $table) {
            $table->id();
            $table->morphs('Tokenable');
            $table->string('Name');
            $table->string('Token', 64)->unique();
            $table->text('Abilities')->nullable();
            $table->timestamp('LastUsedAt')->nullable();
            $table->timestamp('ExpiresAt')->nullable();
            $table->dateTime('CreatedAt');
            $table->dateTime('UpdatedAt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PersonalAccessToken');
    }
};
