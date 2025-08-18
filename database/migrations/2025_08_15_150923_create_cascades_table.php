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
        Schema::create('cascades', function (Blueprint $table) {
            $table->id();
            $table->string("nodal_code",50);
            $table->foreign("nodal_code")->references("site_code")->on('sites')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('cascade_code',50)->unique();
            $table->string('cascade_name',200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cascades');
    }
};
