<?php

use App\Enums\Teams;
use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Models\Site\Site;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

/*
 * Scope rules these tests pin down:
 *
 *   - global roles (super_admin, site_admin, modification_admin) ignore team/zone
 *   - team-scoped roles reach their own team only
 *   - zone-scoped roles reach their own zone only
 *   - a user with no team reaches nothing
 *
 * Written before SitePolicy was rewritten, so they describe intent rather
 * than the current implementation.
 */

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->site = Site::factory()->zone(Zones::CAIRO_EAST)->create();   // HGLI / CE
    $this->otherZoneSite = Site::factory()->zone(Zones::GIZA)->create(); // HGLI / GZ
    $this->otherTeamSite = Site::factory()->zone(Zones::SINAI)->create(); // NGLI / SI
});

function userWith(UserRoles $role, ?Zones $zone = null, ?Teams $team = null): User
{
    $factory = User::factory();

    if ($zone) {
        $factory = $factory->zone($zone);
    } elseif ($team) {
        $factory = $factory->team($team);
    }

    return $factory->role($role)->create();
}

/* ---------------------------------------------------------------- view --- */

it('lets anyone in an operational team view a site', function () {
    $user = userWith(UserRoles::SITE_ENGINEER, team: Teams::HGLI);

    expect($user->can('view', $this->site))->toBeTrue();
    expect($user->can('view', $this->otherTeamSite))->toBeTrue();
});

it('lets a global role view a site without belonging to a team', function () {
    $user = userWith(UserRoles::SUPER_ADMIN);

    expect($user->can('view', $this->site))->toBeTrue();
});

it('refuses a user with no team and no global role', function () {
    $user = User::factory()->create();

    expect($user->can('view', $this->site))->toBeFalse();
});

/* ------------------------------------------------ viewSiteProperties --- */

it('lets a zone manager view properties of a site in their own zone', function () {
    $user = userWith(UserRoles::ZONE_MANAGER, Zones::CAIRO_EAST);

    expect($user->can('viewSiteProperties', $this->site))->toBeTrue();
});

it('stops a zone manager viewing properties of a site in another zone', function () {
    $user = userWith(UserRoles::ZONE_MANAGER, Zones::CAIRO_EAST);

    expect($user->can('viewSiteProperties', $this->otherZoneSite))->toBeFalse();
});

it('lets a global role view properties anywhere', function () {
    $user = userWith(UserRoles::SITE_ADMIN);

    expect($user->can('viewSiteProperties', $this->site))->toBeTrue();
    expect($user->can('viewSiteProperties', $this->otherTeamSite))->toBeTrue();
});

/* ------------------------------------------------- create and update --- */

it('only lets a site admin create sites', function () {
    expect(userWith(UserRoles::SITE_ADMIN)->can('create', Site::class))->toBeTrue();
    expect(userWith(UserRoles::SUPER_ADMIN)->can('create', Site::class))->toBeTrue();
    expect(userWith(UserRoles::TEAM_MANAGER, team: Teams::HGLI)->can('create', Site::class))->toBeFalse();
    expect(userWith(UserRoles::ZONE_MANAGER, Zones::CAIRO_EAST)->can('create', Site::class))->toBeFalse();
});

it('only lets a site admin update a site', function () {
    expect(userWith(UserRoles::SITE_ADMIN)->can('update', $this->site))->toBeTrue();
    expect(userWith(UserRoles::ZONE_MANAGER, Zones::CAIRO_EAST)->can('update', $this->site))->toBeFalse();
});

/* -------------------------------------------- viewSiteModifications --- */

it('lets a team member view modifications of a site in their team', function () {
    $user = userWith(UserRoles::SITE_ENGINEER, team: Teams::HGLI);

    expect($user->can('viewSiteModifications', $this->site))->toBeTrue();
});

it('stops a team member viewing modifications of a site in another team', function () {
    $user = userWith(UserRoles::SITE_ENGINEER, team: Teams::HGLI);

    expect($user->can('viewSiteModifications', $this->otherTeamSite))->toBeFalse();
});

it('lets a modification admin view modifications in every team', function () {
    $user = userWith(UserRoles::MODIFICATION_ADMIN);

    expect($user->can('viewSiteModifications', $this->site))->toBeTrue();
    expect($user->can('viewSiteModifications', $this->otherTeamSite))->toBeTrue();
});

/* ------------------------------------------ createSiteModifications --- */

it('lets a zone manager create modifications in their own zone only', function () {
    $user = userWith(UserRoles::ZONE_MANAGER, Zones::CAIRO_EAST);

    expect($user->can('createSiteModifications', $this->site))->toBeTrue();
    expect($user->can('createSiteModifications', $this->otherZoneSite))->toBeFalse();
});

it('lets a site engineer create modifications anywhere in their team', function () {
    $user = userWith(UserRoles::SITE_ENGINEER, team: Teams::HGLI);

    expect($user->can('createSiteModifications', $this->site))->toBeTrue();
    expect($user->can('createSiteModifications', $this->otherZoneSite))->toBeTrue();
    expect($user->can('createSiteModifications', $this->otherTeamSite))->toBeFalse();
});

it('lets a team manager create modifications across their team', function () {
    $user = userWith(UserRoles::TEAM_MANAGER, team: Teams::HGLI);

    expect($user->can('createSiteModifications', $this->site))->toBeTrue();
    expect($user->can('createSiteModifications', $this->otherTeamSite))->toBeFalse();
});

it('stops a plain user creating modifications', function () {
    $user = User::factory()->zone(Zones::CAIRO_EAST)->create();

    expect($user->can('createSiteModifications', $this->site))->toBeFalse();
});

/* ------------------------------------------------------- destructive --- */

it('never allows deleting a site, super admins excepted by the gate', function () {
    /*
     * The policy refuses this to everyone. Super admins are the one
     * exception, and not through the policy at all: the Gate::before in
     * AppServiceProvider answers true before it is consulted.
     */
    expect(userWith(UserRoles::SITE_ADMIN)->can('delete', $this->site))->toBeFalse();
    expect(userWith(UserRoles::SITE_ADMIN)->can('forceDelete', $this->site))->toBeFalse();

    expect(userWith(UserRoles::SUPER_ADMIN)->can('delete', $this->site))->toBeTrue();
});

/* ------------------------------------ the bug the Phase 0 audit found --- */

it('scopes a zone manager by their zone column, not by a role name', function () {
    // Previously the role name carried the zone (CE_zone_manager), and 8 of 12
    // managers held the wrong one. Scope now comes from users.zone.
    $manager = userWith(UserRoles::ZONE_MANAGER, Zones::SINAI);

    expect($manager->zone)->toBe(Zones::SINAI);
    expect($manager->team)->toBe(Teams::NGLI);
    expect($manager->can('createSiteModifications', $this->otherTeamSite))->toBeTrue();
    expect($manager->can('createSiteModifications', $this->site))->toBeFalse();
});
