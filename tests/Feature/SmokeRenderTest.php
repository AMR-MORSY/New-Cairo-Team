<?php

use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Support\Facades\Route;

/*
 * Renders every parameterless GET route as a super admin.
 *
 * This exists because the Flux -> daisyUI conversion touched 56 view files
 * that the feature tests never render. A blade component with a bad prop
 * throws at render time, not at compile time, so compiling is not enough.
 */

test('every parameterless page renders without a server error', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $admin = User::factory()
        ->zone(Zones::CAIRO_EAST)
        ->role(UserRoles::SUPER_ADMIN)
        ->create();

    $uris = collect(Route::getRoutes())
        ->filter(fn ($route) => in_array('GET', $route->methods(), true))
        ->reject(fn ($route) => str_contains($route->uri(), '{'))      // needs a model
        ->reject(fn ($route) => str_starts_with($route->uri(), 'flux/'))
        ->reject(fn ($route) => str_starts_with($route->uri(), 'livewire/'))
        ->reject(fn ($route) => str_starts_with($route->uri(), '_'))
        ->reject(fn ($route) => str_starts_with($route->uri(), 'wireui/'))   // asset endpoints, not pages
        ->reject(fn ($route) => in_array($route->uri(), ['up'], true))
        ->map(fn ($route) => '/' . ltrim($route->uri(), '/'))
        ->unique()
        ->values();

    expect($uris)->not->toBeEmpty();

    $failures = [];

    foreach ($uris as $uri) {
        try {
            $status = $this->actingAs($admin)->get($uri)->status();

            if ($status >= 500) {
                $failures[] = "{$uri} -> HTTP {$status}";
            }
        } catch (Throwable $e) {
            $failures[] = "{$uri} -> " . class_basename($e) . ': ' . $e->getMessage();
        }
    }

    expect($failures)->toBe([], "pages failed to render:\n  " . implode("\n  ", $failures));
});
