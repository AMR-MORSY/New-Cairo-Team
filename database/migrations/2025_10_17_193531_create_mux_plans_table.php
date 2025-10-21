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
        Schema::create('mux_plans', function (Blueprint $table) {
            $table->id();
            $table->string('ne', 50)->nullable();
            $table->string('ne_ip', 50)->nullable();
            $table->string('ne_slot', 50)->nullable();
            $table->string('fe', 50)->nullable();
            $table->string('fe_slot', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mux_plans');
    }
};
