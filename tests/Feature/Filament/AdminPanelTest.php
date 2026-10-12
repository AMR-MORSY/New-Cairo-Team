<?php

use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Filament\Resources\Modification\Actions\ActionResource;
use App\Filament\Resources\Modification\Projects\ProjectResource;
use App\Filament\Resources\Modification\Requesters\RequesterResource;
use App\Filament\Resources\Modification\Subcontractors\Pages\CreateSubcontractor;
use App\Filament\Resources\Modification\Subcontractors\Pages\EditSubcontractor;
use App\Filament\Resources\Modification\Subcontractors\SubcontractorResource;
use App\Filament\Resources\Site\SiteNoticeTypes\SiteNoticeTypeResource;
use App\Models\Modification\Subcontractor;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

/*
 * The Filament panel edits the lookup tables the modification forms pick
 * from. Two things matter more than the CRUD itself:
 *
 *   - it lets any authenticated user in unless told otherwise, and these
 *     screens are not for every engineer
 *   - modifications.subcontractor_id and .requester_id are ON DELETE CASCADE,
 *     so a delete button here would take thousands of modifications with it
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

it('lets an administrator into the panel', function () {
    expect($this->admin->canAccessPanel(Filament\Facades\Filament::getPanel('admin')))->toBeTrue();

    $this->actingAs($this->admin)->get('/admin')->assertSuccessful();
});

it('keeps everyone else out of the panel', function () {
    expect($this->engineer->canAccessPanel(Filament\Facades\Filament::getPanel('admin')))->toBeFalse();

    $this->actingAs($this->engineer)->get('/admin')->assertForbidden();
});

it('sends a guest to the sign-in page rather than a second login', function () {
    // the panel's own ->login() was removed; the app has one front door
    expect(Route::has('filament.admin.auth.login'))->toBeFalse();

    $this->get('/admin')->assertRedirect();
});

it('opens each lookup list for an administrator', function (string $resource) {
    $this->actingAs($this->admin)
        ->get($resource::getUrl('index'))
        ->assertSuccessful();
})->with([
    'subcontractors' => SubcontractorResource::class,
    'projects' => ProjectResource::class,
    'requesters' => RequesterResource::class,
    'actions' => ActionResource::class,
    'site notice types' => SiteNoticeTypeResource::class,
]);

it('refuses those lists to a non-administrator', function (string $resource) {
    $this->actingAs($this->engineer)
        ->get($resource::getUrl('index'))
        ->assertForbidden();
})->with([
    'subcontractors' => SubcontractorResource::class,
    'projects' => ProjectResource::class,
    'requesters' => RequesterResource::class,
    'actions' => ActionResource::class,
    'site notice types' => SiteNoticeTypeResource::class,
]);

it('offers no way to delete a lookup row', function (string $resource) {
    expect($resource::canDeleteAny())->toBeFalse();
})->with([
    'subcontractors' => SubcontractorResource::class,
    'projects' => ProjectResource::class,
    'requesters' => RequesterResource::class,
    'actions' => ActionResource::class,
    'site notice types' => SiteNoticeTypeResource::class,
]);

it('can add a subcontractor that is not already in the table', function () {
    $this->actingAs($this->admin);

    Livewire\Livewire::test(CreateSubcontractor::class)
        ->fillForm(['name' => 'BRAND NEW CO', 'is_active' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Subcontractor::where('name', 'BRAND NEW CO')->exists())->toBeTrue();
});

it('refuses a duplicate name', function () {
    Subcontractor::create(['name' => 'ALREADY THERE', 'is_active' => true]);

    $this->actingAs($this->admin);

    Livewire\Livewire::test(CreateSubcontractor::class)
        ->fillForm(['name' => 'ALREADY THERE', 'is_active' => true])
        ->call('create')
        ->assertHasFormErrors(['name']);
});

it('retires an entry by toggling it inactive', function () {
    $sub = Subcontractor::create(['name' => 'RETIRE ME', 'is_active' => true]);

    $this->actingAs($this->admin);

    Livewire\Livewire::test(EditSubcontractor::class, ['record' => $sub->getKey()])
        ->fillForm(['name' => 'RETIRE ME', 'is_active' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($sub->fresh()->is_active)->toBeFalsy();
});
