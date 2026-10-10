<?php

use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Spatie\Permission\Models\Role;

/*
 * User, role and permission administration is super-admin only.
 *
 * These routes used to carry nothing but 'auth', so any authenticated user
 * could open them by typing the URL; only the sidebar link was hidden. The
 * sidebar and the routes now share the 'handle_users_activities' ability, so
 * a regression in one is a regression in both.
 */

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()
        ->zone(Zones::CAIRO_EAST)
        ->role(UserRoles::SUPER_ADMIN)
        ->create();
});

dataset('administration pages', [
    'user list' => 'users.index',
    'role list' => 'role.index',
    'permission list' => 'permission.index',
]);

test('a super admin reaches it', function (string $route) {
    $this->actingAs($this->admin)
        ->get(route($route))
        ->assertSuccessful();
})->with('administration pages');

test('a non-admin is refused', function (string $route) {
    $engineer = User::factory()
        ->zone(Zones::CAIRO_EAST)
        ->role(UserRoles::SITE_ENGINEER)
        ->create();

    $this->actingAs($engineer)
        ->get(route($route))
        ->assertForbidden();
})->with('administration pages');

test('a user with no role at all is refused', function (string $route) {
    $nobody = User::factory()->zone(Zones::CAIRO_EAST)->create();

    $this->actingAs($nobody)
        ->get(route($route))
        ->assertForbidden();
})->with('administration pages');

test('a guest is sent to the sign-in page', function (string $route) {
    $this->get(route($route))->assertRedirect(route('home', absolute: false));
})->with('administration pages');

test('the model-bound administration pages are gated too', function () {
    $engineer = User::factory()
        ->zone(Zones::CAIRO_EAST)
        ->role(UserRoles::SITE_ENGINEER)
        ->create();

    // a site engineer cannot inspect another user, nor even their own record
    $this->actingAs($engineer)->get(route('user.show', $this->admin))->assertForbidden();
    $this->actingAs($engineer)->get(route('user.show', $engineer))->assertForbidden();

    $role = Role::query()->firstOrFail();
    $this->actingAs($engineer)->get(route('role.permissions', $role))->assertForbidden();

    $this->actingAs($this->admin)->get(route('user.show', $this->admin))->assertSuccessful();
});

test('the sidebar only offers the users menu to those who can open it', function () {
    $engineer = User::factory()
        ->zone(Zones::CAIRO_EAST)
        ->role(UserRoles::SITE_ENGINEER)
        ->create();

    $this->actingAs($this->admin)->get('/dashboard')
        ->assertSee(route('role.index'));

    $this->actingAs($engineer)->get('/dashboard')
        ->assertDontSee(route('role.index'));
});
