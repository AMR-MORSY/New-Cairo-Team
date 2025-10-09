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
        Schema::create('site_power_data', function (Blueprint $table) {
            $table->id();
            $table->string("site_code", 50);
            $table->foreign('site_code')->references('site_code')->on('sites')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string("power_source", 50)->nullable();
            $table->string("power_meter_type", 50)->nullable();
            $table->string("gen_config", 50)->nullable();
            $table->string("gen_serial", 50)->nullable();
            $table->string("gen_capacity", 50)->nullable();
            $table->integer("overhaul_consumption")->nullable();
            $table->string("c_length", 50)->nullable();
            $table->string("c_size", 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_power_data');
    }
};
