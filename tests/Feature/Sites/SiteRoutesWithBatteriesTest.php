<?php

use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Livewire\Sites\Actions\SiteRoutesWithBatteries;
use App\Livewire\Tables\Site\NodalSitesTable;
use App\Models\Site\Battery;
use App\Models\Site\Site;
use App\Models\Site\SiteRoute;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->engineer = User::factory()->zone(Zones::CAIRO_EAST)->role(UserRoles::MAINTENANCE_ENGINEER)->create();

    $sites = [
        'HUB01A' => ['HUB-ONE', 'Normal'], 'MID01A' => ['MIDDLE', 'NDL'], 'ALT01A' => ['ALTERNATE', 'NDL'],
        'VIP01A' => ['VIP-SITE', 'VIP + NDL'], 'VIP02A' => ['LONELY-VIP', 'VIP'], 'TP001A' => ['3P-SHOP', 'Normal'],
    ];

    foreach ($sites as $code => [$name, $category]) {
        Site::create([
            'site_code' => $code, 'site_name' => $name, 'zone' => 'CE', 'team' => 'HGLI', 'type' => 'Macro',
            'category' => $category, 'severity' => 'Bronze', 'sharing' => 'No', 'status' => 'On Air',
        ]);
    }

    // VIP01A reached two ways, sharing HUB01A > MID01A
    foreach (['HUB01A>MID01A', 'HUB01A>MID01A>VIP01A', 'HUB01A>MID01A>ALT01A', 'HUB01A>MID01A>ALT01A>VIP01A'] as $path) {
        $chain = explode('>', $path);
        SiteRoute::create([
            'source' => 'test', 'hub_code' => 'HUB01A', 'hub_name' => 'HUB-ONE', 'parent_code' => $chain[count($chain) - 2],
            'child_code' => end($chain), 'child_name' => $sites[end($chain)][0], 'level' => count($chain) - 1,
            'sheet_row' => 1, 'path' => $path,
        ]);
    }

    Battery::create(['site_code' => 'MID01A', 'batteries_brand' => 'Narada', 'no_strings' => 2, 'category' => 'New', 'installation_date' => '2024-07-01']);
    Battery::create(['site_code' => 'VIP01A', 'batteries_brand' => 'Lithium', 'no_strings' => 1, 'category' => 'Used', 'installation_date' => '2025-01-10']);
});

it('lists the same sites as the table on each page', function (string $target) {
    $table = new NodalSitesTable();
    $table->target = $target;

    $routes = new SiteRoutesWithBatteries();
    $routes->target = $target;

    expect(array_column($routes->cards(), 'code'))
        ->toEqualCanonicalizing($table->datasource()->pluck('site_code')->map(fn ($c) => strtoupper($c))->all());
})->with(['vip', 'TP', 'GSquare']);

it('draws merged routes with names, and the batteries of every site on them', function () {
    Livewire::actingAs($this->engineer)
        ->test(SiteRoutesWithBatteries::class, ['target' => 'vip'])
        ->assertSeeInOrder(['VIP01A', 'HUB01A', 'HUB-ONE', '&lt;&gt;', 'MID01A', 'MIDDLE', '├─', 'VIP01A', '└─', 'ALT01A', 'ALTERNATE'], false)
        ->assertSee('Narada')
        ->assertSee('Lithium')
        ->assertSee('no batteries')                                   // HUB01A and ALT01A have none
        ->assertSee('LONELY-VIP')
        ->assertSee('Not in any live route of the planning sheet.');  // VIP02A
});

it('narrows the list by code or name', function () {
    Livewire::actingAs($this->engineer)
        ->test(SiteRoutesWithBatteries::class, ['target' => 'vip'])
        ->set('search', 'lonely')
        ->assertSee('VIP02A')
        ->assertDontSee('VIP-SITE');
});

it('exports the routes and batteries to Excel', function () {
    Excel::fake();

    Livewire::actingAs($this->engineer)
        ->test(SiteRoutesWithBatteries::class, ['target' => 'vip'])
        ->call('export');

    Excel::assertDownloaded('vip-sites-routes-' . now()->format('Y-m-d') . '.xlsx', function ($export) {
        $rows = collect($export->array());
        $vip01 = $rows->where(0, 'VIP01A');

        // one row per battery, a blank-battery row per site without any
        expect($vip01->pluck(4)->values()->all())->toBe(['HUB01A', 'MID01A', 'VIP01A', 'ALT01A']);
        expect($vip01->firstWhere(4, 'MID01A')[6])->toBe('Narada');
        expect($vip01->firstWhere(4, 'HUB01A')[6])->toBeNull();
        expect($vip01->first()[3])->toBe(
            "HUB01A HUB-ONE <> MID01A MIDDLE <> VIP01A VIP-SITE\n"
            . 'HUB01A HUB-ONE <> MID01A MIDDLE <> ALT01A ALTERNATE <> VIP01A VIP-SITE'
        );
        expect($rows->firstWhere(0, 'VIP02A')[3])->toBe('No live route');

        return true;
    });
});

it('keeps people without CE access out', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(SiteRoutesWithBatteries::class, ['target' => 'vip'])
        ->assertForbidden();
});

it('shows both tabs on the CE VIP, TP and G.SQR pages', function (string $route) {
    $this->actingAs($this->engineer)
        ->get(route($route))
        ->assertOk()
        ->assertSee('Table')
        ->assertSee('Routes');
})->with(['site.CE.sites.vip', 'site.CE.sites.TP', 'site.CE.sites.golden']);
