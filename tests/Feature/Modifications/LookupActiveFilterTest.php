<?php

use App\Models\Modification\Action;
use App\Models\Modification\Project;
use App\Models\Modification\Requester;
use App\Models\Modification\Subcontractor;

/*
 * Retiring a lookup entry has to do two things at once: keep it off the
 * pickers for new records, and keep it on the ones already using it.
 *
 * The second half is the easy one to get wrong. If a retired subcontractor
 * disappears from the edit form, the select falls back to some other value
 * and saving the modification quietly reassigns it.
 */

it('leaves retired entries off the list for new records', function (string $model) {
    $model::create(['name' => 'STILL USED', 'is_active' => true]);
    $model::create(['name' => 'RETIRED', 'is_active' => false]);

    $names = $model::active()->pluck('name');

    expect($names)->toContain('STILL USED');
    expect($names)->not->toContain('RETIRED');
})->with([
    'subcontractors' => Subcontractor::class,
    'projects' => Project::class,
    'requesters' => Requester::class,
    'actions' => Action::class,
]);

it('keeps a retired entry available to the record that already uses it', function (string $model) {
    $model::create(['name' => 'STILL USED', 'is_active' => true]);
    $retired = $model::create(['name' => 'RETIRED', 'is_active' => false]);

    $names = $model::activeOr($retired->id)->pluck('name');

    expect($names)->toContain('STILL USED');
    expect($names)->toContain('RETIRED');
})->with([
    'subcontractors' => Subcontractor::class,
    'projects' => Project::class,
    'requesters' => Requester::class,
    'actions' => Action::class,
]);

it('does not widen the list when a record has no value yet', function () {
    Subcontractor::create(['name' => 'STILL USED', 'is_active' => true]);
    Subcontractor::create(['name' => 'RETIRED', 'is_active' => false]);

    foreach ([null, ''] as $empty) {
        expect(Subcontractor::activeOr($empty)->pluck('name'))->not->toContain('RETIRED');
    }
});

it('casts is_active to a boolean', function () {
    $sub = Subcontractor::create(['name' => 'CAST ME', 'is_active' => 1]);

    expect($sub->fresh()->is_active)->toBeTrue();
});
