<?php

use App\Enums\Teams;
use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Models\Site\Site;
use App\Livewire\Sites\Actions\SiteBatteries;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

/*
 * authorize() supplies the signed-in user itself. Anything passed as the
 * second argument arrives at the policy AFTER $user, and Gate picks which
 * policy to use from the first of those arguments -- so passing Auth::user()
 * sent it looking for a UserPolicy, found none, fell back to a named ability
 * that does not exist, and denied everyone including the zone manager the
 * page is for.
 */

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->site = Site::factory()->zone(Zones::CAIRO_EAST)->create();
});

it('lets the zone manager for the site open its batteries', function () {
    $user = User::factory()->zone(Zones::CAIRO_EAST)->role(UserRoles::ZONE_MANAGER)->create();

    expect($user->isZoneManager($this->site->zone))->toBeTrue();

    $this->actingAs($user)
        ->get(route('site.batteries', $this->site->site_code))
        ->assertSuccessful();
});

it('lets the team manager for the site open its batteries', function () {
    $user = User::factory()->team($this->site->team)->role(UserRoles::TEAM_MANAGER)->create();

    $this->actingAs($user)
        ->get(route('site.batteries', $this->site->site_code))
        ->assertSuccessful();
});

it('refuses someone scoped to another zone', function () {
    $user = User::factory()->zone(Zones::ALEX)->role(UserRoles::ZONE_MANAGER)->create();

    $this->actingAs($user)
        ->get(route('site.batteries', $this->site->site_code))
        ->assertForbidden();
});

/*
 * The route already carries ->can('viewSiteProperties', 'site'), so the tests
 * above pass with or without the check inside mount(). These mount the
 * component directly, which is the only place the component's own authorize()
 * is observable -- and where the Auth::user() argument denied everyone.
 */

it('authorizes inside mount, not only at the route', function () {
    $user = User::factory()->zone(Zones::CAIRO_EAST)->role(UserRoles::ZONE_MANAGER)->create();

    Livewire::actingAs($user)
        ->test(SiteBatteries::class, ['site' => $this->site])
        ->assertOk();
});

it('refuses a wrong-zone user when mounted directly', function () {
    $user = User::factory()->zone(Zones::ALEX)->role(UserRoles::ZONE_MANAGER)->create();

    // Livewire's harness turns the AuthorizationException into a response
    Livewire::actingAs($user)
        ->test(SiteBatteries::class, ['site' => $this->site])
        ->assertForbidden();
});
