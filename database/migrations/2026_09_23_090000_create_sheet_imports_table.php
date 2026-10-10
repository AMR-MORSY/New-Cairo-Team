<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per planning sheet, recording what was last imported from it.
 *
 * The planners edit these sheets constantly, so the app has to notice on its
 * own rather than wait for someone to run a command. The fingerprint is what
 * makes that cheap: comparing it is a stat() call, where re-reading the
 * workbook to find out whether anything changed costs about 1.7 seconds.
 *
 * It also gives the UI an honest "as of" date, so nobody has to guess whether
 * what they are looking at is current.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sheet_imports', function (Blueprint $table) {
            $table->id();

            $table->string('source', 60)->unique();
            $table->string('path', 512);

            // size + mtime of the file as imported
            $table->string('fingerprint', 100);

            $table->unsignedInteger('hops')->default(0);
            $table->unsignedInteger('sites')->default(0);
            $table->unsignedSmallInteger('unreadable_cells')->default(0);

            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sheet_imports');
    }
};
