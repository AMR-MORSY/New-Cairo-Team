<?php

namespace Database\Factories\Site;

use App\Enums\SiteCategories;
use App\Enums\SiteSeverities;
use App\Enums\SiteSharing;
use App\Enums\SiteTypies;
use App\Enums\Status;
use App\Enums\Teams;
use App\Enums\Zones;
use App\Models\Site\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Site\Site>
 */
class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        $zone = fake()->randomElement(Zones::cases());

        return [
            'site_code' => strtoupper(fake()->unique()->bothify('####??')),
            'site_name' => strtoupper(fake()->unique()->bothify('SITE-####')),
            'BSC' => null,
            'RNC' => null,
            'office' => fake()->city(),
            'type' => fake()->randomElement(SiteTypies::cases())->value,
            'category' => fake()->randomElement(SiteCategories::cases())->value,
            'severity' => fake()->randomElement(SiteSeverities::cases())->value,
            'sharing' => SiteSharing::NO->value,
            'host' => null,
            'gest' => null,
            'zone' => $zone,
            'team' => $zone->team(),
            'cells_2G' => 3,
            'cells_3G' => 6,
            'cells_4G' => 9,
            'status' => Status::ON_AIR->value,
        ];
    }

    /** Place the site in a specific zone; the team follows from it. */
    public function zone(Zones $zone): static
    {
        return $this->state(fn () => [
            'zone' => $zone,
            'team' => $zone->team(),
        ]);
    }

    /** Place the site in a team, picking one of that team's zones. */
    public function team(Teams $team): static
    {
        return $this->state(function () use ($team) {
            $zone = fake()->randomElement(Zones::forTeam($team));

            return ['zone' => $zone, 'team' => $team];
        });
    }
}
