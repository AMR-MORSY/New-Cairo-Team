<?php

use App\Models\Site\SheetImport;
use App\Models\Site\SiteRoute;

/*
 * The planners edit these workbooks constantly, so the app re-reads them on
 * its own. Reading one costs ~1.7s, which is why the scheduler compares a
 * fingerprint first: that is a stat() call, and the workbook is only opened
 * when its size or timestamp has moved.
 */

beforeEach(function () {
    $this->sheet = base_path('New Cairo MMUID.xlsx');
});

it('imports the sheet and records what it read', function () {
    $this->artisan('routes:import-sheet', [
        'path' => $this->sheet,
        '--source' => 'watch-test',
    ])->assertSuccessful();

    $import = SheetImport::query()->where('source', 'watch-test')->firstOrFail();

    expect($import->hops)->toBe(SiteRoute::where('source', 'watch-test')->count());
    expect($import->sites)->toBeGreaterThan(400);
    expect($import->imported_at)->not->toBeNull();
    expect($import->fingerprint)->toBe(SheetImport::fingerprint($this->sheet));
})->skip(fn () => ! is_readable(base_path('New Cairo MMUID.xlsx')), 'sheet not present');

it('skips re-reading a sheet that has not changed', function () {
    $this->artisan('routes:import-sheet', [
        'path' => $this->sheet,
        '--source' => 'watch-test',
    ])->assertSuccessful();

    $first = SheetImport::query()->where('source', 'watch-test')->firstOrFail()->imported_at;

    $this->artisan('routes:import-sheet', [
        'path' => $this->sheet,
        '--source' => 'watch-test',
        '--if-changed' => true,
    ])->expectsOutputToContain('unchanged')->assertSuccessful();

    $second = SheetImport::query()->where('source', 'watch-test')->firstOrFail()->imported_at;

    expect($second->equalTo($first))->toBeTrue();
})->skip(fn () => ! is_readable(base_path('New Cairo MMUID.xlsx')), 'sheet not present');

it('re-reads once the fingerprint no longer matches', function () {
    $this->artisan('routes:import-sheet', [
        'path' => $this->sheet,
        '--source' => 'watch-test',
    ])->assertSuccessful();

    // as though the planner had saved the workbook again
    SheetImport::query()->where('source', 'watch-test')->update(['fingerprint' => 'stale']);

    $this->artisan('routes:import-sheet', [
        'path' => $this->sheet,
        '--source' => 'watch-test',
        '--if-changed' => true,
    ])->assertSuccessful();

    expect(SheetImport::query()->where('source', 'watch-test')->firstOrFail()->fingerprint)
        ->toBe(SheetImport::fingerprint($this->sheet));
})->skip(fn () => ! is_readable(base_path('New Cairo MMUID.xlsx')), 'sheet not present');

it('reports a missing sheet rather than failing silently', function () {
    $this->artisan('routes:import-sheet', [
        'path' => base_path('no-such-sheet.xlsx'),
        '--if-changed' => true,
    ])->assertFailed();
});

it('fingerprints a file by size and modification time', function () {
    expect(SheetImport::fingerprint(base_path('composer.json')))
        ->toBe(filesize(base_path('composer.json')) . ':' . filemtime(base_path('composer.json')));

    expect(SheetImport::fingerprint(base_path('nope.xlsx')))->toBeNull();
});

it('watches every sheet named in config', function () {
    $sheets = config('sheets.routes');

    expect($sheets)->toBeArray()->not->toBeEmpty();

    foreach ($sheets as $source => $path) {
        expect($source)->toBeString();
        expect($path)->toBeString();
    }
});
