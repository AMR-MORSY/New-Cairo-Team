<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $teams = Area::with('zones')->get();

        foreach ($teams as $team) {
            $areaManagerRole = Role::where('name', $team->code . '_area_manager')
                ->where('team_id', $team->id)
                ->firstOrFail();

            $zoneManagerRole = Role::where('name', $team->code . '_zone_manager')
                ->where('team_id', $team->id)
                ->firstOrFail();

            $siteEngineerRole = Role::where('name', $team->code . '_site_engineer')
                ->where('team_id', $team->id)
                ->firstOrFail();
            // Create Team Manager
            $teamManager = User::create([
                'name' => $team->code . ' Team Manager',
                'email' => strtolower($team->code) . '_team_manager@company.com',
                'password' => bcrypt("@Mobinil@2020"),
            ]);

            $team->users()->attach($teamManager->id, [
                'zone_id' => null,
                'status' => 'active'
            ]);

            // Assign team manager role
           
              DB::table('model_has_roles')->insert([
                'role_id' => $areaManagerRole->id,
                'model_type' => User::class,
                'model_id' => $teamManager->id,
                'team_id' => $team->id // ← THIS IS THE KEY
            ]);
         
            // Create Zone Managers and site Engineers for each zone
            foreach ($team->zones as $zone) {
                // Zone Manager
                $zoneManager = User::create([
                    'name' => $zone->name . ' Zone Manager',
                    'email' => strtolower($team->code . '_' . $zone->code . '_zone_manager@company.com'),
                    'password' => bcrypt("@Mobinil@2020"),
                ]);
                $team->users()->attach($zoneManager->id, [
                    'zone_id' => $zone->id,
                    'status' => 'active'
                ]);
           
                  DB::table('model_has_roles')->insert([
                    'role_id' => $zoneManagerRole->id,
                    'model_type' => User::class,
                    'model_id' => $zoneManager->id,
                    'team_id' => $team->id
                ]);
             

                // site Engineers (2 per zone)
                for ($i = 1; $i <= 2; $i++) {
                    $engineer = User::create([
                        'name' => $zone->name . ' Site Engineer ' . $i,
                        'email' => strtolower($team->code . '_' . $zone->code . '_engineer_' . $i . '@company.com'),
                        'password' => bcrypt("@Mobinil@2020"),
                    ]);

                    $team->users()->attach($engineer->id, [
                        'zone_id' => $zone->id,
                        'status' => 'active'
                    ]);

                   
                        DB::table('model_has_roles')->insert([
                        'role_id' => $siteEngineerRole->id,
                        'model_type' => User::class,
                        'model_id' => $engineer->id,
                        'team_id' => $team->id
                    ]);
                   
                }
            }
        }
    }
}
