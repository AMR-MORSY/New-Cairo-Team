<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * One site data record per site: Site::site_data is a hasOne, so a second row
 * was never shown and could not be updated or deleted from the app.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_data', function (Blueprint $table) {
            $table->unique('site_code');
        });
    }

    public function down(): void
    {
        Schema::table('site_data', function (Blueprint $table) {
            // the foreign key needs an index on site_code, so put a plain one back first
            $table->index('site_code');
            $table->dropUnique(['site_code']);
        });
    }
};
