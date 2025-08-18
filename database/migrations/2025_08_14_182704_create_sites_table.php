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
        Schema::create('sites', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string("site_code", 20)->unique();
            $table->string("site_name", 50);
            $table->string("BSC", 50)->nullable();
            $table->string("RNC", 50)->nullable();
            $table->string('office', 50)->nullable();

            $table->string('type', 50)->nullable();

            $table->string('category', 50)->nullable();

            $table->string('severity', 50)->nullable();

            $table->string('sharing', 20)->nullable();

            $table->string('host', 20)->nullable();

            $table->string('gest', 50)->nullable();
            $table->string("vf_code", 50)->nullable();
            $table->string("et_code", 50)->nullable();
            $table->string("we_code", 50)->nullable();

            $table->string('oz', 50)->nullable();

              $table->string("zone", 20)->nullable();
            $table->integer("2G_cells")->nullable();
            $table->integer("3G_cells")->nullable();

            $table->string("status", 20)->default("On Air");
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
