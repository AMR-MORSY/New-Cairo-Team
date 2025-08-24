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

            $table->enum('type',array_column(SiteTypies::cases(),'value'));

            $table->enum('category', array_column(SiteCategories::cases(),'value'));

            $table->enum('severity',array_column(SiteSeverities::cases(),'value'));

            $table->enum('sharing', array_column(SiteSharing::cases(),'value'));

            $table->enum('host', array_column(Host::cases(),'value'))->nullable();

            $table->enum('gest', array_column(Guest::cases(),'value'))->nullable();
            $table->string("vf_code", 50)->nullable();
            $table->string("et_code", 50)->nullable();
            $table->string("we_code", 50)->nullable();

           $table->foreignId('zone_id')->constrained()->onDelete('cascade');

            $table->foreignId('area_id')->constrained()->onDelete('cascade');
            $table->integer("cells_2G")->nullable();
            $table->integer("cells_3G")->nullable();
            $table->integer("cells_4G")->nullable();
            $table->enum("status",array_column(Status::cases(),'value'))->default("On Air");

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
