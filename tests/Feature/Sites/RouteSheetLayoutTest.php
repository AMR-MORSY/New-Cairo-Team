<?php

use App\Services\Sites\RouteSheetParser;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

/*
 * The parser used to carry the column positions as constants, which only held
 * for one version of one workbook. Inserting a single column shifted every
 * level: the old positions saw 555 of 767 site cells and reported success on
 * the rest. It happened for real on 23 Sep, when a column was added in front
 * of the hub.
 *
 * So the layout is read off the sheet. Exact-position assertions use a
 * workbook built here, never the live planning sheet -- that one is edited
 * constantly and a test that pins its shape would fail on the planners' work
 * rather than on a bug.
 */

beforeEach(function () {
    $this->tmp = [];
});

afterEach(function () {
    foreach ($this->tmp ?? [] as $file) {
        @unlink($file);
    }
});

function saveBook(Spreadsheet $book, array &$tmp): string
{
    $path = sys_get_temp_dir() . '/sheet-' . uniqid() . '.xlsx';
    (new XlsxWriter($book))->save($path);
    $tmp[] = $path;

    return $path;
}

/**
 * A miniature planning sheet: hub, two cascade levels, then an MMU pair and
 * a third level, laid out the way the real ones are.
 */
function fixtureBook(): Spreadsheet
{
    $book = new Spreadsheet();
    $ws = $book->getActiveSheet();

    // A: hub (merged down)   B, C: levels   D, E: mmu pair   F: level 3
    $ws->fromArray([
        ['0001CA HUB', '0002CA LEVEL-ONE', '0003CA LEVEL-TWO', 'NR83', 'NR93', '0004CA LEVEL-THREE'],
        [null, null, '0005CA LEVEL-TWO-B', null, null, null],
        [null, '0006CA LEVEL-ONE-B', '0007CA LEVEL-TWO-C', 'NI86', 'NI96', '0008CA LEVEL-THREE-B'],
    ], null, 'A1');

    $ws->mergeCells('A1:A3');

    return $book;
}

it('reads the column layout off the sheet', function () {
    $path = saveBook(fixtureBook(), $this->tmp);

    $layout = (new RouteSheetParser())->layout($path);

    expect($layout['site_cols'])->toBe([0, 1, 2, 5]);
    expect($layout['mmu_cols'])->toBe([5 => [3, 4]]);
});

it('shifts the detected columns to match an inserted column', function () {
    $book = fixtureBook();
    $book->getActiveSheet()->insertNewColumnBefore('A', 2);

    $layout = (new RouteSheetParser())->layout(saveBook($book, $this->tmp));

    // everything moved two to the right, and the parser followed
    expect($layout['site_cols'])->toBe([2, 3, 4, 7]);
    expect($layout['mmu_cols'])->toBe([7 => [5, 6]]);
});

it('picks up a cascade level added on the right', function () {
    $book = fixtureBook();
    $ws = $book->getActiveSheet();
    $ws->setCellValue('G1', 'NRC1');
    $ws->setCellValue('H1', 'NRD1');
    $ws->setCellValue('I1', '0009CA LEVEL-FOUR');

    $layout = (new RouteSheetParser())->layout(saveBook($book, $this->tmp));

    expect($layout['site_cols'])->toBe([0, 1, 2, 5, 8]);
    expect($layout['mmu_cols'])->toBe([5 => [3, 4], 8 => [6, 7]]);
});

it('refuses a workbook it cannot recognise rather than importing nothing', function () {
    $book = new Spreadsheet();
    $book->getActiveSheet()->fromArray([['hello', 'world'], ['a', 'b']]);

    expect(fn () => (new RouteSheetParser())->parse(saveBook($book, $this->tmp)))
        ->toThrow(RuntimeException::class, 'Could not find the site columns');
});

/*
 * These run against the live sheet but assert nothing about its shape -- only
 * that editing it the way a planner would leaves the parse unchanged. That
 * stays true however often the planners rework it.
 */

it('still parses the same hops after a column is inserted', function (string $before, int $count) {
    $sheet = base_path('New Cairo MMUID.xlsx');

    if (! is_readable($sheet)) {
        $this->markTestSkipped('planning sheet not present');
    }

    $baseline = (new RouteSheetParser())->parse($sheet);

    $book = IOFactory::createReader('Xlsx')->load($sheet);
    $book->getActiveSheet()->insertNewColumnBefore($before, $count);

    $hops = (new RouteSheetParser())->parse(saveBook($book, $this->tmp));

    expect(count($hops))->toBe(count($baseline));
    expect(array_column($hops, 'path'))->toBe(array_column($baseline, 'path'));
})->with([
    'one before B' => ['B', 1],
    'one before E' => ['E', 1],
    'two before A' => ['A', 2],
]);

it('is unaffected by rows inserted at the top of the live sheet', function () {
    $sheet = base_path('New Cairo MMUID.xlsx');

    if (! is_readable($sheet)) {
        $this->markTestSkipped('planning sheet not present');
    }

    $baseline = (new RouteSheetParser())->parse($sheet);

    $book = IOFactory::createReader('Xlsx')->load($sheet);
    $book->getActiveSheet()->insertNewRowBefore(1, 3);

    expect(count((new RouteSheetParser())->parse(saveBook($book, $this->tmp))))
        ->toBe(count($baseline));
});
