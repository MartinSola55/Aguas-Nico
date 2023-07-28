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
        Schema::table('products_dispatched', function (Blueprint $table) {
            $table->integer('bottle_types_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_dispatched', function (Blueprint $table) {
            $table->dropColumn('bottle_types_id');
        });
    }
};
