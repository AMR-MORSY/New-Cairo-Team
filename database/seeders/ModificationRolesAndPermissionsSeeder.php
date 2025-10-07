<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\ModificationPermissions;
use App\Enums\Zones;
use App\Models\Team;
use App\Models\Zone;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModificationRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create base permissions
        $permissions =  array_column(ModificationPermissions::cases(), "value");

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $teams = Team::all();

        foreach ($teams as $team) {
            // Create team-specific roles
            $teamManagerRole = Role::create(['name' => $team->code . '_team_manager', 'team_id' => $team->id, 'guard_name' => 'web']);
            if ($team->zones->count() > 0) {
                foreach ($team->zones as $zone) {
                    $zoneCode = Zones::getCodeByValue($zone->code);
                    $zoneManagerRole = Role::create(['name' => $zoneCode . '_zone_manager', 'team_id' => $team->id, 'guard_name' => 'web']);
                    $zoneManagerRole->givePermissionTo(ModificationPermissions::zoneManagerRoles());
                }
            }

            $siteEngineerRole = Role::create(['name' => $team->code . '_site_engineer', 'team_id' => $team->id, 'guard_name' => 'web']);

            // Assign permissions to Team Manager
            $teamManagerRole->givePermissionTo(
                ModificationPermissions::teamManagerRoles()
            );

            $siteEngineerRole->givePermissionTo(ModificationPermissions::siteEngineerRoles());
        }
    }
}
