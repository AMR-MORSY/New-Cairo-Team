<?php

use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Livewire\Modifications\ModificationLinks;
use App\Models\Modification\Modification;
use App\Models\Site\Site;
use App\Models\User;
use Database\Seeders\ProjectsSeeder;
use Database\Seeders\RequestersSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SubcontractorsSeeder;
use Livewire\Livewire;

/*
 * The delete confirmation used to be WireUI's dialog()->confirm(). It is now a
 * Filament Action mounted from the daisyUI navlist row rather than rendered as
 * a Filament button, so these pin both halves: the trigger stays a navlist
 * item, and the modal Filament builds carries the copy we wrote.
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

    $this->modification = Modification::factory()
        ->forSite($this->site)
        ->ownedBy($this->admin)
        ->create();
});

function links(string $page = 'details'): \Livewire\Features\SupportTesting\Testable
{
    return Livewire::actingAs(test()->admin)->test(ModificationLinks::class, [
        'site' => test()->site,
        'modification' => test()->modification,
        'page' => $page,
    ]);
}

it('keeps the delete trigger as a navlist row, not a filament button', function () {
    // the quick-links panel only renders its rows on the pages it belongs to,
    // so this has to go through the real route rather than Livewire::test
    $html = $this->actingAs($this->admin)
        ->get(route('modification.details', $this->modification))
        ->assertOk()
        ->getContent();

    expect($html)->toContain("mountAction('delete')")
        // a Filament button would bring its own markup and break the sidebar
        ->and($html)->not->toContain('fi-btn');
});

it('renders the filament modal container inside the component view', function () {
    // <x-filament-actions::modals /> reads $this, so it cannot live in the layout
    expect(links()->html())->toContain('action-modals');
});

it('loads the filament js the modal needs on a non-panel page', function () {
    $html = $this->actingAs($this->admin)
        ->get(route('modification.details', $this->modification))
        ->getContent();

    // actions.js registers the filamentActionModals Alpine component the
    // modal markup binds to; without @filamentScripts it never opens
    expect($html)->toContain('js/filament/actions/actions.js')
        ->and($html)->toContain('js/filament/support/support.js');
});

it('compiles the fi-* styles into our own css build', function () {
    // @filamentStyles links no stylesheet outside a panel -- the fi-* rules
    // live in the panel theme, which also carries a second Tailwind preflight
    $css = file_get_contents(resource_path('css/app.css'));

    expect($css)->toContain('vendor/filament/support/resources/css/index.css')
        ->and($css)->toContain('vendor/filament/actions/resources/css/index.css')
        ->and($css)->toContain("@source '../../vendor/filament/actions/resources/views'");
});

it('builds the confirmation with our copy when the action is mounted', function () {
    // Filament ships modal markup as a Livewire "partials" effect, not in the
    // component's own render, so assert there
    $component = links()->call('mountAction', 'delete');

    $modal = $component->effects['partials']['action-modals'] ?? '';

    expect($modal)->toContain('Delete this modification?')
        ->and($modal)->toContain('releases the reserved amount back to the purchase order')
        ->and($modal)->toContain('Yes, delete it')
        ->and($modal)->toContain('fi-modal');
});

it('resolves the action by name with a deferred callback', function () {
    $action = (new ModificationLinks())->deleteAction();

    expect($action->getName())->toBe('delete')
        ->and($action->isConfirmationRequired())->toBeTrue()
        ->and($action->getModalHeading())->toBe('Delete this modification?')
        ->and($action->getModalSubmitActionLabel())->toBe('Yes, delete it');
});

it('keeps the quick links after the modal is dismissed', function () {
    // the branches used to read request()->routeIs(), true only on the first
    // page load: unmounting the action posts to /livewire/update, where every
    // branch was false and the whole panel re-rendered empty
    $component = links('details');

    expect($component->html())->toContain('Quick Links')
        ->and($component->html())->toContain("mountAction('delete')");

    $afterCancel = $component->call('mountAction', 'delete')->call('unmountAction')->html();

    expect($afterCancel)->toContain('Quick Links')
        ->and($afterCancel)->toContain("mountAction('delete')")
        ->and($afterCancel)->toContain('Update')
        ->and($afterCancel)->toContain('Back');
});

it('renders the right links for each page it is placed on', function () {
    expect(links('modifications')->html())->toContain('New')
        ->and(links('modifications')->html())->not->toContain('mountAction');

    expect(links('update')->html())->toContain('Details')
        ->and(links('update')->html())->toContain('mountAction');

    // a page that passes no page prop gets an empty panel, as before
    expect(links('')->html())->not->toContain('mountAction');
});
