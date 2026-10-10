<?php

use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Http\Middleware\CheckTeamMembership;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/*
 * CheckTeamMembership gates /site, /modification and /nur with abort(403).
 * That is not an ability and not a policy, so the Gate::before in
 * AppServiceProvider never runs for it -- every role allowed to hold no team
 * has to be named in the middleware itself.
 *
 * The middleware is invoked directly here. Going through a route would mix
 * its verdict with the route's ->can() and the component's own authorize(),
 * and a 403 from those looks identical.
 */

beforeEach(fn () => $this->seed(RolesAndPermissionsSeeder::class));

function passesTeamCheck(User $user): bool
{
    test()->actingAs($user);

    try {
        (new CheckTeamMembership())->handle(Request::create('/site'), fn ($r) => response('ok'));

        return true;
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(403);

        return false;
    }
}

it('lets a user with a team through', function () {
    expect(passesTeamCheck(User::factory()->zone(Zones::CAIRO_EAST)->create()))->toBeTrue();
});

it('waives the team requirement for the roles that hold no team', function (UserRoles $role) {
    $user = User::factory()->role($role)->create();

    expect($user->team)->toBeNull();
    expect(passesTeamCheck($user))->toBeTrue();
})->with([
    'super admin' => UserRoles::SUPER_ADMIN,
    'modification admin' => UserRoles::MODIFICATION_ADMIN,
    'site admin' => UserRoles::SITE_ADMIN,
]);

it('refuses a teamless user with no role at all', function () {
    expect(passesTeamCheck(User::factory()->create()))->toBeFalse();
});

it('refuses a teamless user holding only a scoped role', function (UserRoles $role) {
    expect(passesTeamCheck(User::factory()->role($role)->create()))->toBeFalse();
})->with([
    'team manager' => UserRoles::TEAM_MANAGER,
    'zone manager' => UserRoles::ZONE_MANAGER,
    'site engineer' => UserRoles::SITE_ENGINEER,
    'maintenance engineer' => UserRoles::MAINTENANCE_ENGINEER,
]);
