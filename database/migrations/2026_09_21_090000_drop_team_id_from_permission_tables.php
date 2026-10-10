<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Removes the Spatie "teams" columns from the permission tables.
 *
 * Every guard checks for the column first, so this is a clean no-op on a
 * database created after config('permission.teams') was set to false.
 *
 * Safe because role names are already globally unique - no two roles share a
 * name across teams - so unique(name, guard_name) applies without collisions.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('roles', 'team_id')) {
            $this->guardAgainstDuplicateRoleNames();

            Schema::table('roles', function (Blueprint $table) {
                $table->dropUnique('roles_team_id_name_guard_name_unique');
                $table->dropIndex('roles_team_foreign_key_index');
                $table->dropColumn('team_id');
                $table->unique(['name', 'guard_name'], 'roles_name_guard_name_unique');
            });
        }

        if (Schema::hasColumn('model_has_roles', 'team_id')) {
            $this->collapsePivot(
                'model_has_roles',
                'role_id',
                'model_has_roles_team_foreign_key_index',
                'model_has_roles_role_model_type_primary'
            );
        }

        if (Schema::hasColumn('model_has_permissions', 'team_id')) {
            $this->collapsePivot(
                'model_has_permissions',
                'permission_id',
                'model_has_permissions_team_foreign_key_index',
                'model_has_permissions_permission_model_type_primary'
            );
        }
    }

    /**
     * Dropping team_id can merge two pivot rows into one. Remove those
     * duplicates first so the new primary key can be created.
     */
    private function collapsePivot(string $table, string $pivotKey, string $teamIndex, string $primaryName): void
    {
        $dupes = DB::table($table)
            ->select($pivotKey, 'model_id', 'model_type', DB::raw('COUNT(*) c'))
            ->groupBy($pivotKey, 'model_id', 'model_type')
            ->having('c', '>', 1)
            ->get();

        foreach ($dupes as $d) {
            $keep = DB::table($table)
                ->where($pivotKey, $d->$pivotKey)
                ->where('model_id', $d->model_id)
                ->where('model_type', $d->model_type)
                ->min('team_id');

            DB::table($table)
                ->where($pivotKey, $d->$pivotKey)
                ->where('model_id', $d->model_id)
                ->where('model_type', $d->model_type)
                ->where('team_id', '!=', $keep)
                ->delete();
        }

        DB::statement("ALTER TABLE `{$table}` DROP PRIMARY KEY");

        Schema::table($table, function (Blueprint $t) use ($teamIndex) {
            $t->dropIndex($teamIndex);
            $t->dropColumn('team_id');
        });

        DB::statement(
            "ALTER TABLE `{$table}` ADD CONSTRAINT `{$primaryName}` PRIMARY KEY (`{$pivotKey}`, `model_id`, `model_type`)"
        );
    }

    private function guardAgainstDuplicateRoleNames(): void
    {
        $dupes = DB::table('roles')
            ->select('name', 'guard_name', DB::raw('COUNT(*) c'))
            ->groupBy('name', 'guard_name')
            ->having('c', '>', 1)
            ->pluck('name')
            ->all();

        if ($dupes) {
            throw new RuntimeException(
                'Cannot drop roles.team_id - these role names exist more than once: '
                . implode(', ', $dupes)
            );
        }
    }

    public function down(): void
    {
        // One-way: re-adding team_id cannot recover which team each row belonged to.
    }
};
