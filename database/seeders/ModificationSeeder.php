<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Models\Modification\Modification;
use App\Enums\ModificationStatus;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Models\Modification\Subcontractor;
use App\Models\Site\Site;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Development modifications: five per site engineer, on sites inside that
 * engineer's own zone.
 *
 * Run RolesAndPermissionsSeeder, UsersSeeder and the lookup seeders first.
 */
class ModificationSeeder extends Seeder
{
    public function run(): void
    {
        $subcontractors = Subcontractor::all()->take(5);
        $requesters = Requester::all();
        $projects = Project::all();
        $statuses = ModificationStatus::cases();

        $engineers = User::withRole(UserRoles::SITE_ENGINEER)
            ->whereNotNull('zone')
            ->get();

        if ($engineers->isEmpty() || $subcontractors->isEmpty()) {
            $this->command?->warn('No site engineers or subcontractors - seed those first.');

            return;
        }

        $created = 0;

        foreach ($engineers as $engineer) {
            $sites = Site::where('zone', $engineer->zone->value)->pluck('site_code');

            if ($sites->isEmpty()) {
                continue;
            }

            foreach ($subcontractors as $subcontractor) {
                $requestDate = Carbon::now()->subDays(rand(1, 365));
                $cwDate = $requestDate->copy()->addDays(rand(1, 15));
                $d6Date = $cwDate->copy()->addDays(rand(1, 30));
                $estCost = rand(10000, 500000) / 100;
                $status = $statuses[array_rand($statuses)];

                // Done (1) and Waiting D6 (3) carry a real final cost.
                $finalCost = $status->isQuoted()
                    ? $estCost * (rand(80, 200) / 100)
                    : 0;

                Modification::create([
                    'team' => $engineer->team,
                    'zone' => $engineer->zone,
                    'action_owner' => $engineer->id,
                    'site_code' => $sites->random(),
                    'subcontractor_id' => $subcontractor->id,
                    'requester_id' => $requesters->random()->id,
                    'description' => '',
                    'pending' => rand(0, 1),
                    'status' => $status,
                    'project_id' => $projects->random()->id,
                    'request_date' => $requestDate->format('Y-m-d'),
                    'd6_date' => $d6Date->format('Y-m-d'),
                    'cw_date' => $cwDate->format('Y-m-d'),
                    'final_cost' => $finalCost,
                    'est_cost' => $estCost,
                    'reported' => rand(0, 1),
                    'reported_at' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 30)) : null,
                ]);

                $created++;
            }
        }

        $this->command?->info("Seeded {$created} modifications.");
    }
}
