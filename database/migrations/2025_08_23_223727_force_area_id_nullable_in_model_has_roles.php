<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::table('model_has_roles', function (Blueprint $table) {
        // Use DB::statement to force the change
        DB::statement('ALTER TABLE model_has_roles MODIFY area_id BIGINT UNSIGNED NULL');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('model_has_roles', function (Blueprint $table) {
        DB::statement('ALTER TABLE model_has_roles MODIFY area_id BIGINT UNSIGNED NOT NULL');
    });
    }
};
