<?php

use App\Enums\Teams;
use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Models\Modification\Modification;
use App\Models\Site\Site;
use App\Models\User;
use Database\Seeders\ModificationActionsSeeder;
use Database\Seeders\ProjectsSeeder;
use Database\Seeders\RequestersSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SubcontractorsSeeder;

beforeEach(function () {
    $this->seed([
        RolesAndPermissionsSeeder::class,
        SubcontractorsSeeder::class,
        ProjectsSeeder::class,
        RequestersSeeder::class,
        ModificationActionsSeeder::class,
    ]);

    $this->ceSite = Site::factory()->zone(Zones::CAIRO_EAST)->create();   // HGLI / CE
    $this->gzSite = Site::factory()->zone(Zones::GIZA)->create();         // HGLI / GZ
    $this->siSite = Site::factory()->zone(Zones::SINAI)->create();        // NGLI / SI

    $this->ceMod = Modification::factory()->forSite($this->ceSite)->create();
    $this->siMod = Modification::factory()->forSite($this->siSite)->create();
});

function modUser(UserRoles $role, ?Zones $zone = null, ?Teams $team = null): User
{
    $f = User::factory();

    if ($zone) {
        $f = $f->zone($zone);
    } elseif ($team) {
        $f = $f->team($team);
    }

    return $f->role($role)->create();
}

/* --------------------------------------------------------- search --- */

it('lets any team member search modifications', function () {
    expect(modUser(UserRoles::SITE_ENGINEER, team: Teams::HGLI)->can('modificationSearch', Modification::class))->toBeTrue();
});

it('lets a modification admin with no team search modifications', function () {
    expect(modUser(UserRoles::MODIFICATION_ADMIN)->can('modificationSearch', Modification::class))->toBeTrue();
});

it('refuses search to a user with no team and no global role', function () {
    expect(User::factory()->create()->can('modificationSearch', Modification::class))->toBeFalse();
});

/* -------------------------------------------------------- details --- */

it('lets a team member see details of a modification in their team', function () {
    $user = modUser(UserRoles::SITE_ENGINEER, team: Teams::HGLI);

    expect($user->can('viewModificationDetails', $this->ceMod))->toBeTrue();
    expect($user->can('viewModificationDetails', $this->siMod))->toBeFalse();
});

it('lets a modification admin see details in every team', function () {
    $user = modUser(UserRoles::MODIFICATION_ADMIN);

    expect($user->can('viewModificationDetails', $this->ceMod))->toBeTrue();
    expect($user->can('viewModificationDetails', $this->siMod))->toBeTrue();
});

/* --------------------------------------------------------- update --- */

it('lets the action owner update their own modification', function () {
    $owner = User::factory()->zone(Zones::CAIRO_EAST)->create();
    $mod = Modification::factory()->forSite($this->ceSite)->ownedBy($owner)->create();

    expect($owner->can('updateModification', $mod))->toBeTrue();
});

it('lets the zone manager of that zone update it', function () {
    $user = modUser(UserRoles::ZONE_MANAGER, Zones::CAIRO_EAST);

    expect($user->can('updateModification', $this->ceMod))->toBeTrue();
});

it('stops a zone manager from another zone updating it', function () {
    $user = modUser(UserRoles::ZONE_MANAGER, Zones::GIZA);

    expect($user->can('updateModification', $this->ceMod))->toBeFalse();
});

it('lets the team manager update anything in their team', function () {
    $user = modUser(UserRoles::TEAM_MANAGER, team: Teams::HGLI);

    expect($user->can('updateModification', $this->ceMod))->toBeTrue();
    expect($user->can('updateModification', $this->siMod))->toBeFalse();
});

it('lets a modification admin update across every team', function () {
    $user = modUser(UserRoles::MODIFICATION_ADMIN);

    expect($user->can('updateModification', $this->ceMod))->toBeTrue();
    expect($user->can('updateModification', $this->siMod))->toBeTrue();
});

it('stops a site engineer updating a modification they do not own', function () {
    $user = modUser(UserRoles::SITE_ENGINEER, team: Teams::HGLI);

    expect($user->can('updateModification', $this->ceMod))->toBeFalse();
});

/* ---------------------------------------------------- destructive --- */

it('never allows deleting a modification, super admins excepted by the gate', function () {
    /*
     * The policy refuses this to everyone. Super admins are the one
     * exception, and not through the policy at all: the Gate::before in
     * AppServiceProvider answers true before it is consulted.
     */
    // modifications.action_owner is ON DELETE CASCADE, so this one matters
    $modAdmin = modUser(UserRoles::MODIFICATION_ADMIN);

    expect($modAdmin->can('delete', $this->ceMod))->toBeFalse();
    expect($modAdmin->can('forceDelete', $this->ceMod))->toBeFalse();

    expect(modUser(UserRoles::SUPER_ADMIN)->can('delete', $this->ceMod))->toBeTrue();
});

/* ------------------------------ regression: the $modification->area bug --- */

it('resolves the modification team without the removed area relation', function () {
    // updateModification used to call $modification->area, which does not
    // exist - passing null into isTeamManager(Team) and raising a TypeError
    // for any non-owner.
    $user = modUser(UserRoles::SITE_ENGINEER, team: Teams::HGLI);

    expect(fn () => $user->can('updateModification', $this->ceMod))->not->toThrow(Throwable::class);
    expect($this->ceMod->team)->toBe(Teams::HGLI);
    expect($this->ceMod->zone)->toBe(Zones::CAIRO_EAST);
});

/* ------------------------------------- status is an enum, not a table --- */

it('casts the modification status to the enum', function () {
    expect($this->ceMod->status)->toBeInstanceOf(App\Enums\ModificationStatus::class);
    expect($this->ceMod->status->isInProgress())->toBeTrue();
    expect($this->ceMod->status->isQuoted())->toBeFalse();
});

it('treats Done and Waiting D6 as quoted, the rest as not', function () {
    expect(App\Enums\ModificationStatus::Done->isQuoted())->toBeTrue();
    expect(App\Enums\ModificationStatus::WAITING_D6->isQuoted())->toBeTrue();
    expect(App\Enums\ModificationStatus::IN_PROGRESS->isQuoted())->toBeFalse();
    expect(App\Enums\ModificationStatus::CANCELLED->isQuoted())->toBeFalse();
});
