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
        Schema::create('site_notices', function (Blueprint $table) {
            $table->id();
            $table->string('site_code');
            $table->foreign("site_code")->references("site_code")->on('sites')->onUpdate("cascade")->onDelete("cascade");
            $table->string('notice_type',50);
            $table->string('title',200);
            $table->text('description');
            $table->boolean('is_solved')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_notices');
    }
};
