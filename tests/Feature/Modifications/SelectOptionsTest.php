<?php

use App\Enums\UserRoles;
use App\Enums\Zones;
use App\Models\Modification\Action;
use App\Models\Modification\Modification;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Models\Modification\Subcontractor;
use App\Models\Site\Site;
use App\Models\User;
use Database\Seeders\ModificationActionsSeeder;
use Database\Seeders\ProjectsSeeder;
use Database\Seeders\RequestersSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SubcontractorsSeeder;

/*
 * The original bug: WireUI's select built each option with
 * array_merge((array) $option, ...). Cast an Eloquent model that way and you
 * get its internal properties -- "\0*\0connection", "\0*\0attributes" and so
 * on -- rather than its columns, so the control rendered as an empty box.
 *
 * WireUI is gone; every option list goes through <x-select-named-options>,
 * which flattens to id/name pairs and emits plain <option> tags (or, for a
 * multiselect, a grid of checkboxes). These tests assert that rendering, so
 * they still catch an option list that arrives empty or full of model state.
 */

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(SubcontractorsSeeder::class);
    $this->seed(RequestersSeeder::class);
    $this->seed(ProjectsSeeder::class);
    $this->seed(ModificationActionsSeeder::class);

    $this->admin = User::factory()
        ->zone(Zones::CAIRO_EAST)
        ->role(UserRoles::SUPER_ADMIN)
        ->create();

    $this->site = Site::factory()->zone(Zones::CAIRO_EAST)->create();
});

/**
 * Every <select> on the page, keyed by the field it is bound to, as a list of
 * [value => label] pairs. The leading placeholder (value="") is dropped.
 *
 * @return array<string, array<string, string>>
 */
function renderedSelects(string $html): array
{
    preg_match_all('/<select\b[^>]*>.*?<\/select>/s', $html, $blocks);

    $selects = [];

    foreach ($blocks[0] as $block) {
        preg_match('/wire:model[^=]*="([^"]+)"/', $block, $model);
        preg_match_all('/<option\b([^>]*)>(.*?)<\/option>/s', $block, $options, PREG_SET_ORDER);

        $pairs = [];

        foreach ($options as $option) {
            preg_match('/value="([^"]*)"/', $option[1], $value);
            $value = $value[1] ?? '';

            if ($value === '') {
                continue;   // the placeholder
            }

            $pairs[$value] = trim(html_entity_decode(strip_tags($option[2])));
        }

        $selects[$model[1] ?? 'unbound'] = $pairs;
    }

    return $selects;
}

/** The values of every checkbox bound to $field -- the multiselect rendering. */
function checkedBoxValues(string $html, string $field): array
{
    preg_match_all('/<input\b[^>]*>/s', $html, $inputs);

    $values = [];

    foreach ($inputs[0] as $input) {
        if (! str_contains($input, 'type="checkbox"') || ! str_contains($input, '"' . $field . '"')) {
            continue;
        }

        if (preg_match('/value="([^"]*)"/', $input, $m)) {
            $values[] = $m[1];
        }
    }

    return $values;
}

it('gives every select real options rather than model internals', function () {
    $html = $this->actingAs($this->admin)
        ->get(route('modification.create', $this->site->site_code))
        ->assertOk()
        ->getContent();

    $selects = renderedSelects($html);

    foreach (['form.subcontractor_id', 'form.project_id', 'form.requester_id', 'form.status'] as $field) {
        expect($selects)->toHaveKey($field);
        expect($selects[$field])->not->toBeEmpty("{$field} rendered with no options");

        foreach ($selects[$field] as $value => $label) {
            expect($label)->not->toBe('', "{$field} has an option with no label");

            // a raw model cast leaked keys and values containing a null byte
            expect(str_contains($label, "\0"))->toBeFalse("{$field} label carries raw model state");
            expect($label)->not->toContain('*')
                ->and($label)->not->toContain('connection')
                ->and($label)->not->toContain('attributes');
        }
    }
});

it('lists the lookup tables by name', function () {
    $html = $this->actingAs($this->admin)
        ->get(route('modification.create', $this->site->site_code))
        ->getContent();

    $selects = renderedSelects($html);

    $expected = [
        'form.subcontractor_id' => Subcontractor::active()->pluck('name', 'id'),
        'form.project_id' => Project::active()->pluck('name', 'id'),
        'form.requester_id' => Requester::active()->pluck('name', 'id'),
    ];

    foreach ($expected as $field => $rows) {
        expect($rows)->not->toBeEmpty("the {$field} seeder produced nothing");

        foreach ($rows as $id => $name) {
            expect($selects[$field])->toHaveKey((string) $id)
                ->and($selects[$field][(string) $id])->toBe($name);
        }
    }
});

it('renders the actions multiselect as one checkbox per action', function () {
    $html = $this->actingAs($this->admin)
        ->get(route('modification.create', $this->site->site_code))
        ->getContent();

    $actions = Action::active()->pluck('name', 'id');

    expect($actions)->not->toBeEmpty();

    // the checkbox grid replaced WireUI's tag picker: a native <select multiple>
    // piles its options onto daisyUI's single-line .select control
    expect(checkedBoxValues($html, 'form.action_id'))
        ->toEqualCanonicalizing($actions->keys()->map('strval')->all());

    foreach ($actions as $name) {
        expect($html)->toContain(e($name));
    }
});

it('does the same on the update form, with the saved values selected', function () {
    $modification = Modification::factory()
        ->forSite($this->site)
        ->ownedBy($this->admin)
        ->create();

    $html = $this->actingAs($this->admin)
        ->get(route('modification.update', $modification))
        ->assertOk()
        ->getContent();

    $selects = renderedSelects($html);

    expect($selects)->toHaveKey('form.subcontractor_id');
    expect($selects['form.subcontractor_id'])->not->toBeEmpty();

    foreach ($selects as $field => $pairs) {
        foreach ($pairs as $label) {
            expect(str_contains($label, "\0"))->toBeFalse("{$field} carries raw model state");
        }
    }

    // the update form must preselect what is stored, not just list the options
    expect($html)->toMatch(
        '/<option[^>]*value="' . preg_quote((string) $modification->subcontractor_id, '/') . '"[^>]*selected/'
    );
});

it('keeps the wrapper between the forms and the raw option arrays', function () {
    $form = file_get_contents(resource_path('views/components/modification-creation-form.blade.php'));

    // a bare <x-select :options> would put an array in the attribute bag, which
    // ComponentAttributeBag::__toString() hands to trim() -- a TypeError
    expect($form)->not->toMatch('/<x-select\s[^>]*:options=/');
});
