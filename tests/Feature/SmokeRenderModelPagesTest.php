<?php

use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Models\Modification\Modification;
use App\Models\Site\Site;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

/*
 * The model-bound pages carry most of the converted form markup, so they are
 * the ones the Flux -> daisyUI change was most likely to break. Renders each
 * as a super admin and fails on anything that 500s.
 */

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    // modifications carry FKs into these lookup tables
    $this->seed(SubcontractorsSeeder::class);
    $this->seed(RequestersSeeder::class);
    $this->seed(ProjectsSeeder::class);

    $this->admin = User::factory()
        ->zone(Zones::CAIRO_EAST)
        ->role(UserRoles::SUPER_ADMIN)
        ->create();

    $this->site = Site::factory()->zone(Zones::CAIRO_EAST)->create();
});

function renderAll(array $routes, $binding, User $admin): array
{
    $failures = [];

    foreach ($routes as $name) {
        try {
            $status = test()->actingAs($admin)->get(route($name, $binding))->status();

            if ($status >= 500) {
                $failures[] = "{$name} -> HTTP {$status}";
            }
        } catch (Throwable $e) {
            $failures[] = "{$name} -> " . class_basename($e) . ': ' . $e->getMessage();
        }
    }

    return $failures;
}

test('site pages render', function () {
    $failures = renderAll([
        'site.show',
        'site.update',
        'site.batteries',
        'site.powerData',
        'site.siteData',
        'site.notes',
        'site.images',
        'site.images.update',
        'site.images.upload',
        'site.cascades.update',
        'site.note.create',
        'powerData.create',
        'siteData.create',
        'battery.create',
        'site.modifications',
        'modification.create',
    ], $this->site, $this->admin);

    expect($failures)->toBe([], "site pages failed:\n  " . implode("\n  ", $failures));
});

test('modification pages render', function () {
    $modification = Modification::factory()
        ->forSite($this->site)
        ->ownedBy($this->admin)
        ->create();

    $failures = renderAll([
        'modification.details',
        'modification.update',
        'quotation.create',
    ], $modification, $this->admin);

    expect($failures)->toBe([], "modification pages failed:\n  " . implode("\n  ", $failures));
});

test('user pages render', function () {
    $failures = renderAll(['user.show'], $this->admin, $this->admin);

    expect($failures)->toBe([], "user pages failed:\n  " . implode("\n  ", $failures));
});
