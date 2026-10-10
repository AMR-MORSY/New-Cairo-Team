<?php

namespace Database\Seeders;

use App\Enums\Teams;
use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Development users: one manager per team, one manager per zone, two site
 * engineers per zone, plus a super admin.
 *
 * Scope is two columns, so the role is the same for everyone doing the same
 * job. The old version looked up a per-zone role inside a loop that had
 * already finished, which silently gave 8 of 12 zone managers the wrong
 * zone's role - that class of bug is not expressible any more.
 *
 * Run RolesAndPermissionsSeeder first.
 */
class UsersSeeder extends Seeder
{
    private const PASSWORD = '@Mobinil@2020';

    public function run(): void
    {
        // $this->make('Super Admin', 'super_admin@company.com', UserRoles::SUPER_ADMIN);
        // $this->make('Site Admin', 'site_admin@company.com', UserRoles::SITE_ADMIN);
        // $this->make('Modification Admin', 'modification_admin@company.com', UserRoles::MODIFICATION_ADMIN);

        // foreach (Teams::cases() as $team) {
        //     $this->make(
        //         $team->value . ' Team Manager',
        //         strtolower($team->value) . '_team_manager@company.com',
        //         UserRoles::TEAM_MANAGER,
        //         $team
        //     );

        //     foreach (Zones::forTeam($team) as $zone) {
        //         $slug = Str::slug($zone->label(), '_');

        //         $this->make(
        //             $zone->label() . ' Zone Manager',
        //             strtolower($team->value . '_' . $slug . '_zone_manager@company.com'),
        //             UserRoles::ZONE_MANAGER,
        //             $team,
        //             $zone
        //         );

        //         for ($i = 1; $i <= 2; $i++) {
        //             $this->make(
        //                 $zone->label() . ' Site Engineer ' . $i,
        //                 strtolower($team->value . '_' . $slug . '_engineer_' . $i . '@company.com'),
        //                 UserRoles::SITE_ENGINEER,
        //                 $team,
        //                 $zone
        //             );
        //         }
        //     }
        // }

         $superAdmin = User::where('email', 'morsy.mamr@gmail.com')->first();
         $superAdmin->syncRoles([UserRoles::SUPER_ADMIN->value]);
         $superAdmin->update(['is_active' => true]);

        $this->command?->info('Super Admin Seeded ' . $superAdmin->email );
    }

    private function make(
        string $name,
        string $email,
        UserRoles $role,
        ?Teams $team = null,
        ?Zones $zone = null
    ): User {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => bcrypt(self::PASSWORD),
                'team' => $team,
                'zone' => $zone,
            ]
        );

        $user->syncRoles([$role->value]);

        return $user;
    }

   
        
}
