<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/*
 * users.team and users.zone are cast to the Teams / Zones enums, which handle
 * null fine but throw on an empty string:
 *
 *   ValueError: "" is not a valid backing value for enum App\Enums\Teams
 *
 * The Filament user form wrote '' rather than null when the selects were left
 * alone, so a user created that way could not be loaded at all -- the cast
 * blew up on hydration, before any policy or middleware ran.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['team', 'zone'] as $column) {
            DB::table('users')->where($column, '')->update([$column => null]);
        }
    }

    public function down(): void
    {
        // '' was never a meaningful value; there is nothing to restore.
    }
};
