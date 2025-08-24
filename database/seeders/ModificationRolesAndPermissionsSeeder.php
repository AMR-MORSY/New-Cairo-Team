<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\ModificationPermissions;
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

        $teams = Area::all();

        foreach ($teams as $team) {
            // Create team-specific roles
            $areaManagerRole = Role::create(['name' => $team->code . '_area_manager','team_id' => $team->id,'guard_name' => 'web']);
            $zoneManagerRole = Role::create(['name' => $team->code . '_zone_manager','team_id' => $team->id,'guard_name' => 'web']);
            $siteEngineerRole = Role::create(['name' => $team->code . '_site_engineer','team_id' => $team->id,'guard_name' => 'web']);

            // Assign permissions to Team Manager
            $areaManagerRole->givePermissionTo(
                ModificationPermissions::areaManagerRoles()
            );
            $zoneManagerRole->givePermissionTo(ModificationPermissions::zoneManagerRoles());
            $siteEngineerRole->givePermissionTo(ModificationPermissions::siteEngineerRoles());
        }
    }
}
