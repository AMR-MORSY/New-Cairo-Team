<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Transmission routes as drawn in the planning sheets.
 *
 * `cascades` cannot hold this: its cascade_code is UNIQUE, so a site gets
 * exactly one parent. In reality a site is often reachable by more than one
 * route, which is why cascades only matches the field about 90% of the time.
 * Here one row is one parent -> child hop, and a site may appear as the child
 * of several parents.
 *
 * Deliberately no foreign keys: roughly one child in eight is an enterprise
 * customer link (Schindler, NBE, Arab Misr Insurance) that has no row in
 * `sites`, and dropping those would lose real hops.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_routes', function (Blueprint $table) {
            $table->id();

            // which sheet this hop came from, so one zone can be re-imported alone
            $table->string('source', 60);

            $table->string('hub_code', 20);        // root of the chain (column A)

            // a pure hub never appears as anyone's child, so its name has to
            // be taken from its own cell or it renders blank
            $table->string('hub_name', 200)->nullable();
            $table->string('parent_code', 20);
            $table->string('child_code', 20);
            $table->string('child_name', 200)->nullable();

            $table->unsignedTinyInteger('level');  // 1 = hangs off the hub
            $table->unsignedSmallInteger('sheet_row');

            /*
             * The whole chain as the sheet draws it, hub first, e.g.
             * "0616CA>0909CA>3637CA>7188CA".
             *
             * Routes are read from here rather than rebuilt by walking
             * parent links: once a few sites have two parents, walking
             * produces every combination of them. 7188CA came back with 19
             * paths that way, most of which nobody ever built. A row of the
             * sheet is one route the planner actually drew.
             */
            $table->string('path', 512);

            // the colour of each column of path, comma separated and blank
            // where the planner left a cell uncoloured
            $table->string('path_fills', 255)->nullable();

            // the label written in each column, which varies row to row:
            // "STH-INVESTORS" on one, "STH-INVESTORS-E-BAND" on another
            $table->json('path_names')->nullable();

            // the two ends of the microwave hop, where the sheet records them
            $table->string('mmu_near', 20)->nullable();
            $table->string('mmu_far', 20)->nullable();

            /*
             * The planners colour cells red / yellow / green. Whatever the
             * code means operationally, it is most of how the sheet reads,
             * so it is carried through rather than flattened away.
             */
            $table->string('fill', 12)->nullable();

            // the cell verbatim, keeping E-Band / XPIC / 2+0 / LDN markers
            $table->string('raw_label', 255)->nullable();

            $table->timestamps();

            $table->index(['source', 'child_code']);
            $table->index(['source', 'parent_code']);
            $table->index(['source', 'hub_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_routes');
    }
};
