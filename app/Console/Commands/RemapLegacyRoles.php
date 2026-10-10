<?php

namespace App\Console\Commands;

use App\Enums\SitePermissions;
use App\Enums\UserRoles;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Re-points existing role assignments from the old team/zone-prefixed roles
 * (HGLI_team_manager, CE_zone_manager, ...) onto the generic set, then deletes
 * the old roles.
 *
 * Run RolesAndPermissionsSeeder first so the target roles exist.
 */
class RemapLegacyRoles extends Command
{
    protected $signature = 'roles:remap-legacy {--dry-run : Report what would change without writing}';

    protected $description = 'Map the old prefixed roles onto the generic role set';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $map = UserRoles::legacyMap();

        $this->renamePermission($dry);

        $moved = 0;
        $merged = 0;
        $deleted = 0;
        $rows = [];

        foreach ($map as $legacyName => $target) {
            $old = Role::where('name', $legacyName)->first();

            if (! $old) {
                continue;
            }

            $new = Role::where('name', $target->value)->first();

            if (! $new) {
                $this->error("Target role [{$target->value}] does not exist - run RolesAndPermissionsSeeder first.");

                return self::FAILURE;
            }

            if ($old->id === $new->id) {
                continue; // already the generic role
            }

            $assignments = DB::table('model_has_roles')->where('role_id', $old->id)->get();
            $thisMoved = 0;
            $thisMerged = 0;

            foreach ($assignments as $a) {
                $exists = DB::table('model_has_roles')
                    ->where('role_id', $new->id)
                    ->where('model_id', $a->model_id)
                    ->where('model_type', $a->model_type)
                    ->exists();

                if ($exists) {
                    $thisMerged++;
                    if (! $dry) {
                        DB::table('model_has_roles')
                            ->where('role_id', $old->id)
                            ->where('model_id', $a->model_id)
                            ->where('model_type', $a->model_type)
                            ->delete();
                    }
                } else {
                    $thisMoved++;
                    if (! $dry) {
                        DB::table('model_has_roles')
                            ->where('role_id', $old->id)
                            ->where('model_id', $a->model_id)
                            ->where('model_type', $a->model_type)
                            ->update(['role_id' => $new->id]);
                    }
                }
            }

            if (! $dry) {
                $old->delete();
            }

            $moved += $thisMoved;
            $merged += $thisMerged;
            $deleted++;

            $rows[] = [$legacyName, $target->value, $thisMoved, $thisMerged];
        }

        $this->table(['legacy role', 'becomes', 'moved', 'merged away'], $rows);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info(sprintf(
            '%s %d assignments moved, %d merged, %d legacy roles removed.',
            $dry ? '[dry run]' : 'Done:',
            $moved,
            $merged,
            $deleted
        ));

        return self::SUCCESS;
    }

    /** view_CE_sites lost its hard-coded zone and became view_zone_sites. */
    private function renamePermission(bool $dry): void
    {
        $old = Permission::where('name', 'view_CE_sites')->first();

        if (! $old) {
            return;
        }

        if (Permission::where('name', SitePermissions::VIEW_ZONE_SITES->value)->exists()) {
            $this->line('  view_CE_sites superseded by view_zone_sites - removing the old one.');
            if (! $dry) {
                $old->delete();
            }

            return;
        }

        $this->line('  renaming permission view_CE_sites -> view_zone_sites');
        if (! $dry) {
            $old->update(['name' => SitePermissions::VIEW_ZONE_SITES->value]);
        }
    }
}
