<?php

use App\Enums\Teams;
use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Models\Modification\PurchaseOrder;
use App\Models\Site\Battery;
use App\Models\Site\Site;
use App\Models\Site\PowerData;
use App\Models\Site\SiteData;
use App\Models\Site\SiteNote;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Gate;

/*
 * Batteries, power data, site data and notes have no policy of their own and
 * no #[UsePolicy]: every page that touches one authorizes against its site
 * instead ($battery->site), which is what the Livewire components do. So the
 * rules below are SitePolicy's, reached through the owning site, and asking
 * Gate about a bare record resolves to no policy at all - false, not a
 * TypeError. The per-route outcome is pinned in SiteRoutesAuthorizationTest.
 */

beforeEach(fn () => $this->seed(RolesAndPermissionsSeeder::class));

dataset('record classes', [
    'battery' => Battery::class,
    'power data' => PowerData::class,
    'site data' => SiteData::class,
    'site note' => SiteNote::class,
]);

it('lets a site admin reach the site that owns these records, owning no team', function () {
    $admin = User::factory()->role(UserRoles::SITE_ADMIN)->create();
    $site = Site::factory()->zone(Zones::CAIRO_EAST)->create();

    expect($admin->team)->toBeNull()
        ->and($admin->can('update', $site))->toBeTrue()
        ->and($admin->can('viewSiteProperties', $site))->toBeTrue();
});

it('refuses scoped roles whatever their team or zone', function () {
    $site = Site::factory()->zone(Zones::CAIRO_EAST)->create();

    $users = [
        User::factory()->zone(Zones::CAIRO_EAST)->role(UserRoles::ZONE_MANAGER)->create(),
        User::factory()->team(Teams::HGLI)->role(UserRoles::TEAM_MANAGER)->create(),
        User::factory()->team(Teams::HGLI)->role(UserRoles::SITE_ENGINEER)->create(),
        User::factory()->create(),
    ];

    foreach ($users as $user) {
        expect($user->can('update', $site))->toBeFalse();
    }
});

it('lets a site admin create these records, which is the site create ability', function () {
    // CreateBattery, CreatePowerData, CreateSiteData and CreateSiteNote all
    // call authorize('create', Site::class) -- there is no per-record ability
    expect(User::factory()->role(UserRoles::SITE_ADMIN)->create()->can('create', Site::class))->toBeTrue();
    expect(User::factory()->team(Teams::HGLI)->role(UserRoles::TEAM_MANAGER)->create()
        ->can('create', Site::class))->toBeFalse();
});

it('resolves no policy for a bare record, so an ability on one is simply false', function (string $class) {
    // nothing authorizes this way any more; if something starts to, it fails
    // closed rather than throwing a TypeError inside SitePolicy
    expect(Gate::getPolicyFor($class))->toBeNull();

    $admin = User::factory()->role(UserRoles::SITE_ADMIN)->create();

    expect($admin->can('update', new $class()))->toBeFalse()
        ->and($admin->can('delete', new $class()))->toBeFalse();
})->with('record classes');

it('never allows deleting a site, super admins excepted by the gate', function () {
    $site = Site::factory()->zone(Zones::CAIRO_EAST)->create();

    expect(User::factory()->role(UserRoles::SITE_ADMIN)->create()->can('delete', $site))->toBeFalse();
    expect(User::factory()->role(UserRoles::SUPER_ADMIN)->create()->can('delete', $site))->toBeTrue();
});

/* --------------------------------------------------- purchase orders --- */

it('limits purchase orders to modification admins', function () {
    $modAdmin = User::factory()->role(UserRoles::MODIFICATION_ADMIN)->create();
    $superAdmin = User::factory()->role(UserRoles::SUPER_ADMIN)->create();
    $teamManager = User::factory()->team(Teams::HGLI)->role(UserRoles::TEAM_MANAGER)->create();

    expect($modAdmin->can('viewPOs', PurchaseOrder::class))->toBeTrue();
    expect($superAdmin->can('viewPOs', PurchaseOrder::class))->toBeTrue();
    expect($teamManager->can('viewPOs', PurchaseOrder::class))->toBeFalse();

    expect($modAdmin->can('viewPurchaseOrderModifications', PurchaseOrder::class))->toBeTrue();
    expect($teamManager->can('viewPurchaseOrderModifications', PurchaseOrder::class))->toBeFalse();
});

it('keeps purchase orders read-only, super admins excepted by the gate', function () {
    /*
     * The policy refuses this to everyone. Super admins are the one
     * exception, and not through the policy at all: the Gate::before in
     * AppServiceProvider answers true before it is consulted.
     */
    $modAdmin = User::factory()->role(UserRoles::MODIFICATION_ADMIN)->create();
    $po = new PurchaseOrder();

    expect($modAdmin->can('create', PurchaseOrder::class))->toBeFalse();
    expect($modAdmin->can('update', $po))->toBeFalse();
    expect($modAdmin->can('delete', $po))->toBeFalse();

    expect(User::factory()->role(UserRoles::SUPER_ADMIN)->create()->can('delete', $po))->toBeTrue();
});
