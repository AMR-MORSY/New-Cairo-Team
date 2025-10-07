<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\User;
use App\Models\Site\Site;
use Illuminate\Database\Seeder;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Models\Modification\Modification;
use App\Models\Modification\Subcontractor;
use App\Models\Modification\ModificationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $subcontractors = Subcontractor::all()->take(5);

        $requesters = Requester::all();
        $projects = Project::all();
        $modificationStatuses = ModificationStatus::all();

        $siteEngineers = []; //////24 site engineers

        foreach ($users as $user) {
            $team = $user->teams()->first();
            if ($team) {
                setPermissionsTeamId($team->id);
                if ($user->hasRole($team->code . '_site_engineer')) {
                    array_push($siteEngineers, $user);
                }
            }
        }




        foreach ($siteEngineers as $engineer) {
            // dd(Site::where('zone_id', $engineer->zones()->first()->id)->get()->random()->site_code);

            foreach ($subcontractors as $subcontractor) { /////////because there are 5 SC, so will create 5 modifications/site Engineer
                $requestDate = Carbon::now()->subDays(rand(1, 365));

                $cwDate = $requestDate->copy()->addDays(rand(1, 15));
                $d6Date = $cwDate->copy()->addDays(rand(1, 30));
                $estCost = rand(10000, 500000) / 100; // Random cost between 10.00 and 500.00
                // if ($modificationStatuses->random()->id == 1 || $modificationStatuses->random()->id == 3) {
                    $finalCost = $estCost * (rand(80, 200) / 100); // Final cost 80-120% of estimated


                // }
                // $finalCost = 0;

                $modification = Modification::create([
                    'team_id' => $engineer->teams()->first()->id,
                    'zone_id' => $engineer->zones()->first()->id,
                    'action_owner' => $engineer->id,
                    'site_code' => Site::where('zone_id', $engineer->zones()->first()->id)->get()->random()->site_code,
                    'subcontractor_id' => $subcontractor->id,
                    'requester_id' => $requesters->random()->id,
                    'description' => '',
                    'pending' => rand(0, 1),
                    'modification_status_id' => $modificationStatuses->random()->id,
                    'project_id' => $projects->random()->id,
                    'request_date' => $requestDate->format('Y-m-d'),
                    'd6_date' => $d6Date->format('Y-m-d'),
                    'cw_date' => $cwDate->format('Y-m-d'),
                    'final_cost' => $finalCost,
                    'est_cost' => $estCost,
                    'reported' => rand(0, 1),
                    'reported_at' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 30)) : null,

                ]);

                $modifications=Modification::all();
                foreach($modifications as $modification)
                {
                    if($modification->modification_status_id==1 || $modification->modification_status_id==3 )
                    {
                        $modification->update(['final_cost'=>$modification->est_cost* (rand(80, 200) / 100)]);
                    }
                    else{
                         $modification->update(['final_cost'=>0]);
                    }
                }
            }
        }
    }
}
