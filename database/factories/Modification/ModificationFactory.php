<?php

namespace Database\Factories\Modification;

use App\Models\Modification\Modification;
use App\Enums\ModificationStatus;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Models\Modification\Subcontractor;
use App\Models\Site\Site;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Modification\Modification>
 *
 * Note: the lookup tables (subcontractors, projects, requesters,
 * modification_status) must be seeded first. When those FKs become
 * enum-backed strings, this factory changes with them.
 */
class ModificationFactory extends Factory
{
    protected $model = Modification::class;

    public function definition(): array
    {
        $site = Site::factory()->create();

        return [
            'site_code' => $site->site_code,
            'zone' => $site->zone,
            'team' => $site->team,
            'subcontractor_id' => Subcontractor::query()->value('id'),
            'requester_id' => Requester::query()->value('id'),
            'status' => ModificationStatus::IN_PROGRESS,
            'project_id' => Project::query()->value('id'),
            'action_owner' => User::factory(),
            'description' => fake()->sentence(),
            'request_date' => now()->toDateString(),
            'est_cost' => 1000,
            'final_cost' => 0,
            'reported' => false,
        ];
    }

    /** Attach the modification to an existing site, inheriting its scope. */
    public function forSite(Site $site): static
    {
        return $this->state(fn () => [
            'site_code' => $site->site_code,
            'zone' => $site->zone,
            'team' => $site->team,
        ]);
    }

    public function ownedBy(User $user): static
    {
        return $this->state(fn () => ['action_owner' => $user->id]);
    }
}
