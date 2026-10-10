<?php

use App\Models\Site\SiteRoute;
use App\Services\Sites\SiteRouteMap;

/*
 * sheetGrid() reproduces the sheet's own layout for the rows a site appears
 * on: the whole row, hub first, with parents spanning their children.
 *
 * Three things are worth guarding, each of which was wrong at some point:
 *   - it starts at the hub, so the upstream half of the route is not lost
 *   - every row naming the site is kept, including ones that stop there
 *   - colours and labels come from that row's own cells, never another row's
 */

/**
 * @param  array<int, ?string>  $fills  one per column of $path, as the sheet
 *                                      colours them on this row
 */
function sheet_hop(int $row, int $level, string $path, array $fills = [], array $extra = []): SiteRoute
{
    $chain = explode('>', $path);
    $fills = array_pad($fills, count($chain), null);

    return SiteRoute::create(array_merge([
        'source' => 'test',
        'hub_code' => $chain[0],
        'hub_name' => 'hub ' . $chain[0],
        'parent_code' => $chain[count($chain) - 2],
        'child_code' => $chain[count($chain) - 1],
        'child_name' => 'n' . $chain[count($chain) - 1],
        'level' => $level,
        'sheet_row' => $row,
        'path' => $path,
        'path_fills' => implode(',', array_map(fn ($f) => (string) $f, $fills)),
        'path_names' => array_map(fn ($c) => 'n' . $c, $chain),
        'fill' => $fills[count($chain) - 1],
    ], $extra));
}

/** flatten the grid to "depth=code(rowspan)" per row, for readable assertions */
function grid_shape(array $grid): array
{
    return array_map(
        fn ($cells) => implode(' ', array_map(
            fn ($c) => "{$c['depth']}={$c['code']}({$c['rowspan']})", $cells)),
        $grid
    );
}

it('includes the route upstream of the site, not just what hangs off it', function () {
    sheet_hop(1, 1, 'HUB01A>PAR01A');
    sheet_hop(1, 2, 'HUB01A>PAR01A>AAA01A');
    sheet_hop(2, 2, 'HUB01A>PAR01A>BBB01A');

    $grid = (new SiteRouteMap('test'))->sheetGrid('PAR01A');

    expect($grid[0][0]['code'])->toBe('HUB01A');
    expect($grid[0][0]['rowspan'])->toBe(2);

    expect(grid_shape($grid))->toBe([
        '0=HUB01A(2) 1=PAR01A(2) 2=AAA01A(1)',
        '2=BBB01A(1)',
    ]);
});

it('keeps a row that stops at the site as its own route', function () {
    // 4167CA is drawn under one hub with a cascade, and under another with
    // none. Dropping the second hid a whole route.
    sheet_hop(1, 1, 'HUBAAA>TGT01A');
    sheet_hop(1, 2, 'HUBAAA>TGT01A>CHD01A');
    sheet_hop(9, 2, 'HUBBBB>MID01A>TGT01A');

    $grid = (new SiteRouteMap('test'))->sheetGrid('TGT01A');

    expect(grid_shape($grid))->toBe([
        '0=HUBAAA(1) 1=TGT01A(1) 2=CHD01A(1)',
        '0=HUBBBB(1) 1=MID01A(1) 2=TGT01A(1)',
    ]);
});

it('never borrows a colour from another row', function () {
    // the same site: red where it is drawn under one hub, uncoloured under
    // the other. Falling back to "whatever colour this code had somewhere"
    // turned the uncoloured one red.
    sheet_hop(1, 2, 'HUBAAA>MID01A>TGT01A', [null, 'yellow', 'red']);
    sheet_hop(9, 1, 'HUBBBB>TGT01A', [null, null]);
    sheet_hop(9, 2, 'HUBBBB>TGT01A>CHD01A', [null, null, 'green']);

    $grid = (new SiteRouteMap('test'))->sheetGrid('TGT01A');
    $target = collect($grid)->flatten(1)->where('code', 'TGT01A')->values();

    expect($target)->toHaveCount(2);
    expect($target[0]['fill'])->toBe('red');
    expect($target[1]['fill'])->toBeNull();
});

it('labels each cell as that row words it', function () {
    // "STH-INVESTORS" on one row, "STH-INVESTORS-E-BAND" on another
    sheet_hop(1, 1, 'HUB01A>TGT01A', [], [
        'path_names' => ['STH-INVESTORS-E-BAND', 'NARGES6'],
    ]);
    sheet_hop(1, 2, 'HUB01A>TGT01A>CHD01A', [], [
        'path_names' => ['STH-INVESTORS-E-BAND', 'NARGES6', 'child'],
    ]);

    $grid = (new SiteRouteMap('test'))->sheetGrid('TGT01A');

    expect($grid[0][0]['name'])->toBe('STH-INVESTORS-E-BAND');
    expect($grid[0][1]['name'])->toBe('NARGES6');
});

it('spans a parent over the rows of its children', function () {
    sheet_hop(1, 1, 'HUB01A>PAR01A');
    sheet_hop(1, 2, 'HUB01A>PAR01A>AAA01A');
    sheet_hop(2, 2, 'HUB01A>PAR01A>BBB01A');
    sheet_hop(3, 2, 'HUB01A>PAR01A>CCC01A');

    $grid = (new SiteRouteMap('test'))->sheetGrid('PAR01A');

    expect(grid_shape($grid))->toBe([
        '0=HUB01A(3) 1=PAR01A(3) 2=AAA01A(1)',
        '2=BBB01A(1)',
        '2=CCC01A(1)',
    ]);
});

it('keeps two links to the same site as separate blocks', function () {
    sheet_hop(1, 1, 'HUB01A>TGT01A', [null, 'red']);
    sheet_hop(1, 2, 'HUB01A>TGT01A>AAA01A', [null, 'red', 'red']);
    sheet_hop(5, 1, 'HUB01A>TGT01A', [null, 'yellow']);
    sheet_hop(5, 2, 'HUB01A>TGT01A>BBB01A', [null, 'yellow', 'yellow']);

    $grid = (new SiteRouteMap('test'))->sheetGrid('TGT01A');
    $target = collect($grid)->flatten(1)->where('code', 'TGT01A')->values();

    expect($target)->toHaveCount(2);
    expect($target->pluck('fill')->all())->toBe(['red', 'yellow']);

    expect($grid[0][0]['code'])->toBe('HUB01A');
    expect($grid[0][0]['rowspan'])->toBe(2);
});

it('carries the planner fill onto each cell', function () {
    sheet_hop(1, 2, 'HUB01A>PAR01A>AAA01A', [null, 'red', 'yellow']);
    sheet_hop(2, 2, 'HUB01A>PAR01A>BBB01A', [null, 'red', 'green']);

    $grid = (new SiteRouteMap('test'))->sheetGrid('HUB01A');
    $byCode = collect($grid)->flatten(1)->keyBy('code');

    expect($byCode['PAR01A']['fill'])->toBe('red');
    expect($byCode['AAA01A']['fill'])->toBe('yellow');
    expect($byCode['BBB01A']['fill'])->toBe('green');
});

it('names the hub even though it is never anyone child', function () {
    sheet_hop(1, 1, 'HUB01A>PAR01A');
    sheet_hop(1, 2, 'HUB01A>PAR01A>AAA01A');

    $grid = (new SiteRouteMap('test'))->sheetGrid('PAR01A');

    expect($grid[0][0]['name'])->toBe('nHUB01A');
});

it('shows a leaf with its route rather than on its own', function () {
    sheet_hop(1, 1, 'HUB01A>MID01A');
    sheet_hop(1, 2, 'HUB01A>MID01A>LEAF1A');

    $grid = (new SiteRouteMap('test'))->sheetGrid('LEAF1A');

    expect(grid_shape($grid))->toBe(['0=HUB01A(1) 1=MID01A(1) 2=LEAF1A(1)']);
});

it('returns an empty grid for a site no sheet mentions', function () {
    sheet_hop(1, 1, 'HUB01A>AAA01A');

    expect((new SiteRouteMap('test'))->sheetGrid('ZZZ99Z'))->toBe([]);
});

it('orders cells left to right within a row', function () {
    sheet_hop(1, 3, 'HUB01A>PAR01A>AAA01A>BBB01A');

    $grid = (new SiteRouteMap('test'))->sheetGrid('HUB01A');

    expect(array_column($grid[0], 'depth'))->toBe([0, 1, 2, 3]);
});
