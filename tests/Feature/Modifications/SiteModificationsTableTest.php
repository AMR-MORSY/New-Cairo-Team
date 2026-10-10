<?php

use App\Enums\ModificationStatus;
use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Livewire\Modifications\Actions\SiteModifications;
use App\Models\Modification\Modification;
use App\Models\Site\Site;
use App\Models\User;
use Database\Seeders\ProjectsSeeder;
use Database\Seeders\RequestersSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SubcontractorsSeeder;
use Livewire\Livewire;

/*
 * The modifications table on a site page.
 *
 * PowerGrid writes each field it builds back onto the row, so a field that
 * shares its name with an enum-cast attribute comes back out as the enum
 * object rather than a string. That produced two failures in a row here:
 * first '"" is not a valid backing value for ModificationStatus' when the
 * field returned an empty string, then 'preg_replace(): Argument #3 must be
 * array|string, ModificationStatus given' once it returned the right value
 * under the clashing name. Hence status_label.
 */

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(SubcontractorsSeeder::class);
    $this->seed(RequestersSeeder::class);
    $this->seed(ProjectsSeeder::class);

    $this->admin = User::factory()
        ->zone(Zones::CAIRO_EAST)
        ->role(UserRoles::SUPER_ADMIN)
        ->create();

    $this->site = Site::factory()->zone(Zones::CAIRO_EAST)->create();
});

it('renders a site modifications page for every status', function (ModificationStatus $status) {
    Modification::factory()
        ->forSite($this->site)
        ->ownedBy($this->admin)
        ->create(['status' => $status]);

    // through the real route: the links partial branches on the current
    // route name, so an isolated component render is not the same page
    $this->actingAs($this->admin)
        ->get(route('site.modifications', $this->site))
        ->assertOk()
        ->assertSee($status->value);
})->with(ModificationStatus::cases());

it('keeps the status field clear of the enum-cast attribute name', function () {
    $table = new App\Livewire\Tables\Modification\SiteModificationsTable();

    $source = file_get_contents((new ReflectionClass($table))->getFileName());

    // a field called 'status' would collide with Modification::$status
    expect($source)->not->toContain("->add('status',");
    expect($source)->toContain("->add('status_label'");
    expect($source)->toContain("Column::make('Status', 'status_label')");
});

it('no longer reaches for the dropped modification_status relation', function () {
    $source = file_get_contents(
        app_path('Livewire/Tables/Modification/SiteModificationsTable.php')
    );

    // the table it pointed at was dropped when status became an enum
    expect($source)->not->toContain('$model->modification_status');
    expect($source)->not->toContain('$modification->modification_status');
});

it('shows the modification with a readable status', function () {
    $modification = Modification::factory()
        ->forSite($this->site)
        ->ownedBy($this->admin)
        ->create(['status' => ModificationStatus::WAITING_D6]);

    $this->actingAs($this->admin)
        ->get(route('site.modifications', $this->site))
        ->assertOk()
        ->assertSee($modification->wo_code)
        ->assertSee(ModificationStatus::WAITING_D6->value);
});
