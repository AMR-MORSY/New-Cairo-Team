<?php

use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Models\Site\Battery;
use App\Models\Site\Site;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

/*
 * routes/site.php no longer carries ->can(): every component authorizes in
 * its own mount() against SitePolicy, so the rule lives in one place. These
 * pin the result per route, which is the only thing that guarantees dropping
 * the route guard did not open anything.
 */

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->site = Site::factory()->zone(Zones::CAIRO_EAST)->create();
    $this->battery = Battery::forceCreate([
        'site_code' => $this->site->site_code, 'batteries_brand' => 'NARADA', 'no_strings' => 2,
    ]);
});

dataset('read routes', [
    'site.show' => 'site.show',
    'site.notes' => 'site.notes',
    'site.batteries' => 'site.batteries',
    'site.powerData' => 'site.powerData',
    'site.siteData' => 'site.siteData',
    'site.images' => 'site.images',
]);

dataset('site-admin-only routes', [
    'site.update' => 'site.update',
    'site.cascades.update' => 'site.cascades.update',
    'site.note.create' => 'site.note.create',
    'battery.create' => 'battery.create',
    'powerData.create' => 'powerData.create',
    'siteData.create' => 'siteData.create',
    'site.images.upload' => 'site.images.upload',
    'site.images.update' => 'site.images.update',
]);

it('keeps read pages to the right scope', function (string $name) {
    $zoneManager = User::factory()->zone(Zones::CAIRO_EAST)->role(UserRoles::ZONE_MANAGER)->create();
    $otherZone = User::factory()->zone(Zones::ALEX)->role(UserRoles::ZONE_MANAGER)->create();

    $this->actingAs($zoneManager)->get(route($name, $this->site->site_code))->assertSuccessful();

    // site.show only needs a team; the rest need the user's own zone
    $response = $this->actingAs($otherZone)->get(route($name, $this->site->site_code));
    $name === 'site.show'
        ? $response->assertSuccessful()
        : $response->assertForbidden();
})->with('read routes');

it('keeps write pages to site admins', function (string $name) {
    $this->actingAs(User::factory()->role(UserRoles::SITE_ADMIN)->create())
        ->get(route($name, $this->site->site_code))->assertSuccessful();

    $this->actingAs(User::factory()->zone(Zones::CAIRO_EAST)->role(UserRoles::ZONE_MANAGER)->create())
        ->get(route($name, $this->site->site_code))->assertForbidden();
})->with('site-admin-only routes');

it('guards the record-bound routes that used to pass the record to the policy', function () {
    $siteAdmin = User::factory()->role(UserRoles::SITE_ADMIN)->create();
    $zoneManager = User::factory()->zone(Zones::CAIRO_EAST)->role(UserRoles::ZONE_MANAGER)->create();

    $this->actingAs($siteAdmin)->get(route('battery.show', $this->battery))->assertSuccessful();
    $this->actingAs($siteAdmin)->get(route('battery.update', $this->battery))->assertSuccessful();

    // a zone manager may look at the site's batteries but not change one
    $this->actingAs($zoneManager)->get(route('battery.show', $this->battery))->assertSuccessful();
    $this->actingAs($zoneManager)->get(route('battery.update', $this->battery))->assertForbidden();
});

it('refuses every one of them to a user with no role and no team', function (string $name) {
    $this->actingAs(User::factory()->create())
        ->get(route($name, $this->site->site_code))
        ->assertForbidden();
})->with('read routes')->with('site-admin-only routes');
