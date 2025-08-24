<?php

namespace Database\Seeders;

use App\Enums\Zones;
use App\Models\Area;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AreasAndZonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamsData = [
            'HGLI' => Zones::HGLIZones(),
            'NGLI' =>Zones::NGLIZones(),
            'AGLI' => Zones::AGLIZones(),
        ];

         foreach ($teamsData as $teamCode => $zones) {
            $team = Area::create([
                'name' => $teamCode . ' Team',
                'code' => $teamCode,
                'description' => $teamCode . ' Operations Team',
            ]);

             foreach ($zones as $zoneName) {
                Zone::create([
                    'area_id' => $team->id,
                    'name' => $zoneName,
                    'code' => strtolower(str_replace('_', '_', $zoneName)),
                    'description' => $zoneName . ' Zone in ' . $teamCode,
                ]);
            }
        }
    }
}
