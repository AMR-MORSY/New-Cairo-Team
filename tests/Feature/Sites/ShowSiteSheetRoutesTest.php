<?php

use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Livewire\Sites\Actions\ShowSite;
use App\Models\Site\Site;
use App\Models\Site\SiteRoute;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

/*
 * The site page shows two things side by side: the single chain the cascades
 * table can express, and every route the planning sheet draws. These tests
 * pin the second, including the case the cascades table cannot represent.
 */

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()
        ->zone(Zones::CAIRO_EAST)
        ->role(UserRoles::SUPER_ADMIN)
        ->create();

    $this->site = Site::factory()->zone(Zones::CAIRO_EAST)->create(['site_code' => 'TGT01A']);
});

function route_hop(string $path, array $extra = []): SiteRoute
{
    // one sheet row per drawn path: SiteRouteMap::sheetRows() groups by
    // sheet_row, so sharing one would merge two separate routes into one
    static $row = 0;
    $row++;

    $chain = explode('>', $path);

    return SiteRoute::create(array_merge([
        'source' => 'sheet',
        'hub_code' => $chain[0],
        'parent_code' => $chain[count($chain) - 2],
        'child_code' => $chain[count($chain) - 1],
        'child_name' => 'name ' . $chain[count($chain) - 1],
        'level' => count($chain) - 1,
        'sheet_row' => $row,
        'path' => $path,
    ], $extra));
}

it('shows both sheet routes for a site the cascades table can only give one', function () {
    route_hop('HUB01A>TGT01A');
    route_hop('HUB01A>MID01A>TGT01A');

    Livewire::actingAs($this->admin)
        ->test(ShowSite::class, ['site' => $this->site])
        ->assertSet('sheetRoutes', fn ($r) => count($r) === 2)
        ->assertSee('Routes from the planning sheet')
        ->assertSee('2 routes')
        ->assertSee('this site is reachable by more than one path')
        ->assertSee('MID01A');
});

it('shows the mmu ids on the hop that has them', function () {
    route_hop('HUB01A>TGT01A', ['mmu_near' => 'NR83', 'mmu_far' => 'NR93']);

    Livewire::actingAs($this->admin)
        ->test(ShowSite::class, ['site' => $this->site])
        ->assertSee('NR83/NR93');
});

it('marks a site that heads its own chain as a hub', function () {
    route_hop('TGT01A>AAA01A');

    Livewire::actingAs($this->admin)
        ->test(ShowSite::class, ['site' => $this->site])
        ->assertSet('isSheetHub', true)
        ->assertSet('sheetRoutes', [])
        ->assertSee('As hub')
        ->assertSee('the sheet draws this site at the head of the chain');
});

it('says so plainly when the sheets know nothing about the site', function () {
    Livewire::actingAs($this->admin)
        ->test(ShowSite::class, ['site' => $this->site])
        ->assertSet('sheetRoutes', [])
        ->assertSet('isSheetHub', false)
        ->assertSee('No route for this site in the imported sheets.');
});

it('still renders the cascades-derived route alongside', function () {
    route_hop('HUB01A>TGT01A');

    Livewire::actingAs($this->admin)
        ->test(ShowSite::class, ['site' => $this->site])
        ->assertOk()
        ->assertSee('Direct Cascade');
});

it('renders the buttons that live in a callout actions slot', function () {
    // these were silently dropped when callout was ported to daisyUI
    Livewire::actingAs($this->admin)
        ->test(ShowSite::class, ['site' => $this->site])
        ->assertSee('UPDATE CASCADES')
        ->assertSee('MUX PLAN');
});
