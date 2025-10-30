<?php

namespace Database\Seeders;

use App\Enums\Zones;
use App\Models\Area;
use App\Models\Team;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TeamsAndZonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamsData = [
            'HGLI' => Zones::HGLIZones(),
            'NGLI' => Zones::NGLIZones(),
            'AGLI' => Zones::AGLIZones(),
        ];

        foreach ($teamsData as $teamCode => $zones) {
            $team = Team::create([
                'name' => $teamCode . ' Team',
                'code' => $teamCode,
                'description' => $teamCode . ' Operations Team',
            ]);

            foreach ($zones as $zoneName) {
                Zone::create([
                    'team_id' => $team->id,
                    'name' => $zoneName,
                    'code' => strtolower(str_replace('_', '_', $zoneName)),
                    'description' => $zoneName . ' Zone in ' . $teamCode,
                ]);
            }
        }
        //////////create GLOBAL Modifications Team
        Team::create([
            'name' => 'Modifications Team',
            'code' => 'Modifications',
            'description' => 'GLOBAL Modifications Team',

        ]);

         //////////create GLOBAL Test Team Team
          Team::create([
            'name' => 'Test Team',
            'code' => 'Test',
            'description' => 'GLOBAL Test Team',

        ]);
    }
}
