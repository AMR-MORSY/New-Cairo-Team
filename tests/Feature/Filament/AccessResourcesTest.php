<?php

use App\Enums\ModificationPermissions;
use App\Enums\SitePermissions;
use App\Enums\Teams;
use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Filament\Resources\Permissions\PermissionResource;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\PermissionGroups;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
 * Users, roles and permissions in the admin panel.
 *
 * The delicate part is the role form: its permission checkboxes are split
 * into panels, and each panel holds its own state. Bind several of them to
 * the one `permissions` relation and each will sync it on save, so whichever
 * runs last wins and the other panels' choices vanish. That is what the
 * "keeps the other panels" test is for.
 */

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $this->admin = User::factory()
        ->zone(Zones::CAIRO_EAST)
        ->role(UserRoles::SUPER_ADMIN)
        ->create();

    $this->engineer = User::factory()
        ->zone(Zones::CAIRO_EAST)
        ->role(UserRoles::SITE_ENGINEER)
        ->create();
});

it('opens each access list for an administrator', function (string $resource) {
    $this->actingAs($this->admin)->get($resource::getUrl('index'))->assertSuccessful();
})->with([
    'users' => UserResource::class,
    'roles' => RoleResource::class,
    'permissions' => PermissionResource::class,
]);

it('refuses each access list to everyone else', function (string $resource) {
    $this->actingAs($this->engineer)->get($resource::getUrl('index'))->assertForbidden();
})->with([
    'users' => UserResource::class,
    'roles' => RoleResource::class,
    'permissions' => PermissionResource::class,
]);

it('never offers to delete a user', function () {
    // modifications.action_owner is ON DELETE CASCADE
    expect(UserResource::canDeleteAny())->toBeFalse();
});

it('assigns a team, zone and role to a user who had none', function () {
    $user = User::factory()->create(['team' => null, 'zone' => null]);
    $role = Role::where('name', UserRoles::ZONE_MANAGER->value)->firstOrFail();

    $this->actingAs($this->admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm([
            'name' => $user->name,
            'email' => $user->email,
            'zone' => Zones::GIZA->value,
            'team' => Zones::GIZA->team()->value,
            'roles' => [$role->id],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $user->refresh();

    expect($user->zone)->toBe(Zones::GIZA);
    expect($user->team)->toBe(Zones::GIZA->team());
    expect($user->hasRole(UserRoles::ZONE_MANAGER->value))->toBeTrue();
});

it('leaves the password alone when the field is left blank', function () {
    $user = User::factory()->create();
    $before = $user->password;

    $this->actingAs($this->admin);

    Livewire::test(EditUser::class, ['record' => $user->getKey()])
        ->fillForm(['name' => 'Renamed', 'email' => $user->email, 'password' => ''])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->fresh()->password)->toBe($before);
    expect($user->fresh()->name)->toBe('Renamed');
});

it('splits a role permissions across the right panels', function () {
    $role = Role::where('name', UserRoles::SUPER_ADMIN->value)->firstOrFail();

    $this->actingAs($this->admin);

    $state = Livewire::test(EditRole::class, ['record' => $role->getKey()])
        ->assertHasNoFormErrors()
        ->get('data');

    $modificationIds = Permission::whereIn('name', array_column(ModificationPermissions::cases(), 'value'))->pluck('id');
    $siteIds = Permission::whereIn('name', array_column(SitePermissions::cases(), 'value'))->pluck('id');

    expect($state[PermissionGroups::field('modifications')])
        ->toEqualCanonicalizing($modificationIds->all());
    expect($state[PermissionGroups::field('sites')])
        ->toEqualCanonicalizing($siteIds->all());
});

it('keeps the other panels when one is saved', function () {
    $role = Role::where('name', UserRoles::SITE_ENGINEER->value)->firstOrFail();

    $keepSite = Permission::where('name', SitePermissions::UPDATE_SITES->value)->firstOrFail();
    $keepMod = Permission::where('name', ModificationPermissions::CREATE_MODIFICATIONS->value)->firstOrFail();

    $this->actingAs($this->admin);

    Livewire::test(EditRole::class, ['record' => $role->getKey()])
        ->fillForm([
            PermissionGroups::field('modifications') => [$keepMod->id],
            PermissionGroups::field('sites') => [$keepSite->id],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $names = $role->fresh()->permissions->pluck('name');

    // both panels survived, and nothing else came along
    expect($names)->toContain($keepMod->name);
    expect($names)->toContain($keepSite->name);
    expect($names)->toHaveCount(2);
});

it('clears a role permissions when every box is unticked', function () {
    $role = Role::where('name', UserRoles::SITE_ADMIN->value)->firstOrFail();

    expect($role->permissions)->not->toBeEmpty();

    $this->actingAs($this->admin);

    Livewire::test(EditRole::class, ['record' => $role->getKey()])
        ->fillForm([
            PermissionGroups::field('modifications') => [],
            PermissionGroups::field('sites') => [],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($role->fresh()->permissions)->toBeEmpty();
});

it('fixes the name of a role the code defines', function () {
    $enumRole = Role::where('name', UserRoles::SUPER_ADMIN->value)->firstOrFail();
    $adhoc = Role::create(['name' => 'ad_hoc', 'guard_name' => 'web']);

    expect(UserRoles::tryFrom($enumRole->name))->not->toBeNull();
    expect(UserRoles::tryFrom($adhoc->name))->toBeNull();

    $this->actingAs($this->admin);

    // renaming the enum-defined one is refused; the ad-hoc one is free
    Livewire::test(EditRole::class, ['record' => $enumRole->getKey()])
        ->fillForm(['name' => 'renamed'])
        ->call('save');

    expect($enumRole->fresh()->name)->toBe(UserRoles::SUPER_ADMIN->value);
});

it('groups every permission that exists', function () {
    $grouped = count(PermissionGroups::allIds());

    expect($grouped)->toBe(Permission::count());
});

it('puts an unrecognised permission in the other panel', function () {
    Permission::create(['name' => 'something_new', 'guard_name' => 'web']);

    $groups = PermissionGroups::all();

    expect($groups)->toHaveKey('other');
    expect($groups['other']['options'])->toContain('Something new');
});
