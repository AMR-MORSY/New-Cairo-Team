<?php

namespace Database\Seeders;

use App\Enums\ModificationPermissions;
use App\Enums\SitePermissions;
use App\Enums\UserRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * The single source of roles and permissions. Both come from enums, so this
 * seeder is idempotent and safe to re-run: it creates what is missing and
 * re-syncs each role's permission set to match App\Enums\UserRoles.
 *
 * Scope (which team or zone a role applies to) is NOT stored here - it comes
 * from users.team / users.zone.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = array_merge(
            array_column(ModificationPermissions::cases(), 'value'),
            array_column(SitePermissions::cases(), 'value'),
        );

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        foreach (UserRoles::cases() as $case) {
            $role = Role::firstOrCreate([
                'name' => $case->value,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($case->permissions());
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command?->info(sprintf(
            'Seeded %d permissions and %d roles.',
            count($permissions),
            count(UserRoles::cases())
        ));
    }
}
