<?php

use App\Enums\Guest;
use App\Enums\Host;
use App\Enums\SiteCategories;
use App\Enums\SiteSeverities;
use App\Enums\SiteSharing;
use App\Enums\SiteTypies;
use App\Enums\Status;
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

            $table->string('type',50);

            $table->string('category', 50);

            $table->string('severity',50);

            $table->string('sharing',20);

            $table->string('host', 20)->nullable();

            $table->string('gest', 50)->nullable();
            $table->string("vf_code", 50)->nullable();
            $table->string("et_code", 50)->nullable();
            $table->string("we_code", 50)->nullable();

           $table->foreignId('zone_id')->constrained()->onDelete('cascade');

            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->integer("cells_2G")->default(0);
            $table->integer("cells_3G")->default(0);
            $table->integer("cells_4G")->default(0);
            $table->string("status",20)->default("On Air");

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
