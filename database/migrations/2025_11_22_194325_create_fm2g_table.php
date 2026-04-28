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
        Schema::create('fm2g', function (Blueprint $table) {
            $table->id();
             $table->string("work_group",30);
            $table->string('Action_OGS_responsible')->nullable();
            $table->text('impacted_sites');
            $table->string('BSC')->nullable();
            $table->integer('cells');
            $table->dateTime("begin");
            $table->dateTime("end");
            $table->decimal("nur", $precision = 8, $scale = 2);
            $table->string("system",100);
            $table->string("sub_system",100);
            $table->text('solution')->nullable();
            $table->string("oz", 50);
            $table->string('type',50);
            $table->boolean("access")->default(0);
            $table->boolean('Force_Majeure')->default(0);
            $table->string('Force_Majeure_type')->nullable();
            $table->string("technology",20)->default("2G");
            $table->string('Dur_Hr');
            $table->integer('Dur_min');
            $table->integer("week");
            $table->integer("year");
            $table->decimal("nur_c", $precision = 8, $scale = 2);
            $table->integer('network_cells_2G');
            $table->integer('total_network_cells');
            $table->string("gen_owner")->nullable();
            $table->string('problem_site_code', 30)->nullable();
            $table->string('problem_site_name', 100)->nullable();
            $table->integer('month');
            $table->decimal('monthly_nur', $precision = 8, $scale = 2);
            $table->string("office",50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fm2g');
    }
};
