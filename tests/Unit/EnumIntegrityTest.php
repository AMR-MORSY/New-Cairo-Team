<?php

use App\Enums\SitePermissions;
use App\Enums\Teams;
use App\Enums\UserRoles;
use App\Enums\Zones;

/*
 * These guard the exhaustive match() arms. Adding a case without its arm
 * throws \UnhandledMatchError at runtime; these turn that into a CI failure.
 */

it('gives every zone a label and an owning team', function () {
    foreach (Zones::cases() as $zone) {
        expect($zone->label())->toBeString()->not->toBeEmpty();
        expect($zone->team())->toBeInstanceOf(Teams::class);
    }
});

it('gives every team a label', function () {
    foreach (Teams::cases() as $team) {
        expect($team->label())->toBeString()->not->toBeEmpty();
    }
});

it('assigns every zone to exactly one team, and every team owns at least one zone', function () {
    $counted = 0;

    foreach (Teams::cases() as $team) {
        $zones = Zones::forTeam($team);
        expect($zones)->not->toBeEmpty("Team {$team->value} owns no zones");
        $counted += count($zones);
    }

    expect($counted)->toBe(count(Zones::cases()));
});

it('keeps zone codes unique and short enough for the column', function () {
    $codes = array_column(Zones::cases(), 'value');

    expect($codes)->toHaveCount(count(array_unique($codes)));

    foreach ($codes as $code) {
        expect(strlen($code))->toBeLessThanOrEqual(20);
    }
});

it('gives every role a label and a non-empty permission set', function () {
    foreach (UserRoles::cases() as $role) {
        expect($role->label())->toBeString()->not->toBeEmpty();
        expect($role->permissions())->toBeArray()->not->toBeEmpty();
    }
});

it('only grants permissions that actually exist', function () {
    $known = array_merge(
        array_column(\App\Enums\ModificationPermissions::cases(), 'value'),
        array_column(SitePermissions::cases(), 'value'),
    );

    foreach (UserRoles::cases() as $role) {
        foreach ($role->permissions() as $permission) {
            expect($known)->toContain($permission);
        }
    }
});

it('grants super_admin every permission', function () {
    $all = array_merge(
        array_column(\App\Enums\ModificationPermissions::cases(), 'value'),
        array_column(SitePermissions::cases(), 'value'),
    );

    expect(UserRoles::SUPER_ADMIN->permissions())
        ->toEqualCanonicalizing($all);
});

it('maps every legacy role name onto a current role', function () {
    foreach (UserRoles::legacyMap() as $legacy => $role) {
        expect($legacy)->toBeString()->not->toBeEmpty();
        expect($role)->toBeInstanceOf(UserRoles::class);
    }
});

it('leaves no permission stranded outside every role', function () {
    $granted = [];

    foreach (UserRoles::cases() as $role) {
        $granted = array_merge($granted, $role->permissions());
    }

    $all = array_merge(
        array_column(\App\Enums\ModificationPermissions::cases(), 'value'),
        array_column(SitePermissions::cases(), 'value'),
    );

    expect(array_values(array_diff($all, array_unique($granted))))->toBe([]);
});
