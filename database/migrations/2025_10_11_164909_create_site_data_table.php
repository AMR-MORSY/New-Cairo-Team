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
        Schema::create('site_data', function (Blueprint $table) {
            $table->id();
            $table->string('site_code');
            $table->foreign("site_code")->references("site_code")->on('sites')->onUpdate("cascade")->onDelete("cascade");
            $table->date("on_air_date")->nullable();
            $table->string("topology", 50)->nullable();
            $table->string("structure", 50)->nullable();
            $table->string("equip_room", 50)->nullable();
            $table->text("address")->nullable();
            $table->string("x_coordinate", 25)->nullable();
            $table->string("y_coordinate", 25)->nullable();
            $table->boolean("ntra_cluster")->default(0);
            $table->boolean("care_ceo")->default(0);
            $table->boolean("axis")->default(0);
            $table->boolean("serve_compound")->default(0);
            $table->boolean("universities")->default(0);
            $table->boolean("hot_spot")->default(0);
            $table->string("network_type", 50)->nullable();
            $table->date("last_pm_date")->nullable();
             $table->boolean("need_access_permission")->default(0);
            $table->string("permission_type", 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_data');
    }
};
