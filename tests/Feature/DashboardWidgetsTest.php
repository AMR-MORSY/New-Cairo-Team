<?php

use App\Enums\ModificationStatus;
use App\Livewire\Dashboard\CostByYearChart;
use App\Livewire\Dashboard\HgliVipSitesPerZoneChart;
use App\Livewire\Dashboard\ModificationsPerMonthChart;
use App\Livewire\Dashboard\ModificationStats;
use App\Livewire\Dashboard\ModificationStatusChart;
use App\Livewire\Dashboard\Overview;
use App\Livewire\Dashboard\SitesPerTeamChart;
use App\Models\Modification\Modification;
use App\Models\Site\Site;
use App\Models\User;
use Database\Seeders\ProjectsSeeder;
use Database\Seeders\RequestersSeeder;
use Database\Seeders\SubcontractorsSeeder;
use Livewire\Livewire;

/*
 * The dashboard is everyone's landing page, so a widget that throws takes
 * the whole app's front door down with it. These pin that each one renders
 * and that the year filter actually reaches the numbers.
 */

beforeEach(function () {
    $this->seed([SubcontractorsSeeder::class, ProjectsSeeder::class, RequestersSeeder::class]);
    $this->actingAs(User::factory()->create());
});

/** Read a protected method's result off a widget instance. */
function widget_call(object $widget, string $method): mixed
{
    return (fn () => $this->{$method}())->call($widget);
}

it('renders the dashboard with every widget', function () {
    Modification::factory()->create(['request_date' => '2025-03-10']);

    $this->get('/dashboard')
        ->assertOk()
        ->assertSeeLivewire(Overview::class)
        ->assertSeeLivewire(ModificationStats::class)
        ->assertSeeLivewire(SitesPerTeamChart::class);
});

it('renders each widget without data', function (string $widget) {
    Livewire::test($widget, ['pageFilters' => ['year' => 2025]])->assertOk();
})->with([
    ModificationStats::class,
    ModificationsPerMonthChart::class,
    ModificationStatusChart::class,
    CostByYearChart::class,
    SitesPerTeamChart::class,
    HgliVipSitesPerZoneChart::class,
]);

it('counts HGLI VIP sites per zone, combined VIP categories included', function () {
    $site = fn (string $zone, string $team, string $category) => Site::factory()->create(['zone' => $zone, 'team' => $team, 'category' => $category]);

    $site('CS', 'HGLI', 'VIP');
    $site('CS', 'HGLI', 'VIP + NDL');
    $site('CS', 'HGLI', 'VIP+NDL');
    $site('CN', 'HGLI', 'VIP');
    $site('CS', 'HGLI', 'Normal');      // not VIP
    $site('CS', 'HGLI', 'NDL');         // not VIP
    $site('CS', 'AGLI', 'VIP');         // another team

    $chart = Livewire::test(HgliVipSitesPerZoneChart::class)->instance();
    $data = widget_call($chart, 'getData');

    expect(array_combine($data['labels'], $data['datasets'][0]['data']))->toBe(['CS' => 3, 'CN' => 1])
        ->and(widget_call($chart, 'getOptions')['plugins']['title']['text'])->toBe('Total: 4');
});

it('defaults to the current year, or the latest one with modifications', function () {
    Modification::factory()->create(['request_date' => '2023-05-01']);
    Modification::factory()->create(['request_date' => '2022-05-01']);

    // no modifications this year, so the latest year with some is picked
    Livewire::test(Overview::class)
        ->assertSet('year', 2023)
        ->assertSet('years', [2023, 2022]);

    Modification::factory()->create(['request_date' => now()->toDateString()]);

    Livewire::test(Overview::class)->assertSet('year', now()->year);
});

it('counts modifications per month for the chosen year only', function () {
    Modification::factory()->create(['request_date' => '2025-03-10']);
    Modification::factory()->create(['request_date' => '2025-03-20']);
    Modification::factory()->create(['request_date' => '2025-11-01']);
    Modification::factory()->create(['request_date' => '2024-03-01']);

    $chart = Livewire::test(ModificationsPerMonthChart::class, ['pageFilters' => ['year' => 2025]])->instance();
    [$current, $previous] = widget_call($chart, 'getData')['datasets'];

    expect($current['label'])->toBe('2025')
        ->and($current['data'][2])->toBe(2)     // March
        ->and($current['data'][10])->toBe(1)    // November
        ->and(array_sum($current['data']))->toBe(3)
        ->and($previous['label'])->toBe('2024')
        ->and($previous['data'][2])->toBe(1);
});

it('splits the chosen year by status', function () {
    Modification::factory()->create(['request_date' => '2025-01-05', 'status' => ModificationStatus::Done]);
    Modification::factory()->create(['request_date' => '2025-02-05', 'status' => ModificationStatus::Done]);
    Modification::factory()->create(['request_date' => '2025-02-05', 'status' => ModificationStatus::WAITING_D6]);
    Modification::factory()->create(['request_date' => '2024-02-05', 'status' => ModificationStatus::Done]);

    $chart = Livewire::test(ModificationStatusChart::class, ['pageFilters' => ['year' => 2025]])->instance();
    $data = widget_call($chart, 'getData');
    $counts = array_combine($data['labels'], $data['datasets'][0]['data']);

    expect($counts)->toBe(['Done' => 2, 'In Progress' => 0, 'Waiting D6' => 1, 'Cancelled' => 0]);
});
