<?php

use App\Models\Site\SiteRoute;
use App\Services\Sites\SiteRouteMap;

/*
 * Routes are read from the path drawn on a sheet row, never rebuilt by
 * following parent links. These tests pin that, because the walking version
 * looked right on simple chains and exploded on real data.
 */

function hop(string $path, array $extra = []): SiteRoute
{
    $chain = explode('>', $path);

    return SiteRoute::create(array_merge([
        'source' => 'test',
        'hub_code' => $chain[0],
        'parent_code' => $chain[count($chain) - 2],
        'child_code' => $chain[count($chain) - 1],
        'child_name' => 'site ' . $chain[count($chain) - 1],
        'level' => count($chain) - 1,
        'sheet_row' => 1,
        'path' => $path,
    ], $extra));
}

it('returns the single route to a site on one chain', function () {
    hop('HUB01A>AAA01A');
    hop('HUB01A>AAA01A>BBB01A');

    $routes = (new SiteRouteMap('test'))->routesTo('BBB01A');

    expect($routes)->toHaveCount(1);
    expect(array_column($routes[0], 'code'))->toBe(['HUB01A', 'AAA01A', 'BBB01A']);
});

it('returns both routes when a site is drawn on two chains', function () {
    hop('HUB01A>AAA01A>TGT01A');
    hop('HUB02A>BBB01A>CCC01A>TGT01A');

    $map = new SiteRouteMap('test');

    expect($map->hasAlternateRoutes('TGT01A'))->toBeTrue();

    $routes = $map->routesTo('TGT01A');
    expect($routes)->toHaveCount(2);

    // shortest first
    expect(array_column($routes[0], 'code'))->toBe(['HUB01A', 'AAA01A', 'TGT01A']);
    expect(array_column($routes[1], 'code'))->toBe(['HUB02A', 'BBB01A', 'CCC01A', 'TGT01A']);
});

it('does not invent routes by combining parents', function () {
    // TGT has two parents, and each parent itself has two. Walking the graph
    // would produce four paths; the sheet only draws two.
    hop('HUB01A>AAA01A>MID01A>TGT01A');
    hop('HUB02A>BBB01A>MID02A>TGT01A');
    hop('HUB01A>AAA01A>MID02A');
    hop('HUB02A>BBB01A>MID01A');

    expect((new SiteRouteMap('test'))->routesTo('TGT01A'))->toHaveCount(2);
});

it('survives a site that appears as its own ancestor', function () {
    // re-homing puts each of these in the other's chain
    hop('HUB01A>AAA01A>BBB01A');
    hop('HUB01A>BBB01A>AAA01A');

    $map = new SiteRouteMap('test');

    expect($map->routesTo('AAA01A'))->toHaveCount(2);
    expect($map->cascadedCodes('AAA01A'))->toBe(['BBB01A']);
});

it('collects everything downstream of a site', function () {
    hop('HUB01A>AAA01A');
    hop('HUB01A>AAA01A>BBB01A');
    hop('HUB01A>AAA01A>BBB01A>CCC01A');
    hop('HUB01A>AAA01A>DDD01A');
    hop('HUB01A>ZZZ01A');           // a different branch

    $map = new SiteRouteMap('test');

    expect($map->cascadedCodes('AAA01A'))->toEqualCanonicalizing(['BBB01A', 'CCC01A', 'DDD01A']);

    $tree = $map->cascadesUnder('AAA01A');
    expect($tree)->toHaveCount(2);
    expect(collect($tree)->firstWhere('code', 'BBB01A')['children'][0]['code'])->toBe('CCC01A');
});

it('leaves red sites out of the cascaded count', function () {
    hop('HUB01A>AAA01A');
    hop('HUB01A>AAA01A>BBB01A', ['fill' => 'red']);      // removed
    hop('HUB01A>AAA01A>CCC01A', ['fill' => 'yellow']);
    hop('HUB01A>AAA01A>DDD01A', ['fill' => 'red']);      // rerouted...
    hop('HUB01A>AAA01A>CCC01A>DDD01A');                 // ...and still drawn here

    expect((new SiteRouteMap('test'))->cascadedCodes('AAA01A'))
        ->toEqualCanonicalizing(['CCC01A', 'DDD01A']);
});

it('drops everything drawn under a red cell, keeping the other route', function () {
    // 3531CA on the sheet: red on one row with four sites under it, yellow on
    // another with one. Only the yellow route still stands.
    hop('HUB01A>MID01A>TGT01A', ['path_fills' => ',,red', 'fill' => 'red']);
    hop('HUB01A>MID01A>TGT01A>OLD01A', ['path_fills' => ',,red,red', 'fill' => 'red']);
    hop('HUB01A>MID01A>TGT01A>OLD02A', ['path_fills' => ',,red,', 'fill' => null]);   // uncoloured, still under red
    hop('HUB01A>MID01A>TGT01A', ['path_fills' => ',,yellow', 'fill' => 'yellow']);
    hop('HUB01A>MID01A>TGT01A>NEW01A', ['path_fills' => ',,yellow,yellow', 'fill' => 'yellow']);

    expect((new SiteRouteMap('test'))->cascadedCodes('TGT01A'))->toBe(['NEW01A']);
});

it('ignores red cells above the site', function () {
    // a red ancestor is another site's problem; it does not hide this one's cascades
    hop('HUB01A>AAA01A>TGT01A>LEAF1A', ['path_fills' => ',red,,']);

    expect((new SiteRouteMap('test'))->cascadedCodes('TGT01A'))->toBe(['LEAF1A']);
});

it('reports no descendants for a leaf', function () {
    hop('HUB01A>AAA01A>LEAF1A');

    expect((new SiteRouteMap('test'))->cascadedCodes('LEAF1A'))->toBe([]);
});

it('carries the mmu ids of the final hop', function () {
    hop('HUB01A>AAA01A>TGT01A', ['mmu_near' => 'NR83', 'mmu_far' => 'NR93']);

    $route = (new SiteRouteMap('test'))->routesTo('TGT01A')[0];
    $last = end($route);

    expect($last['mmu_near'])->toBe('NR83');
    expect($last['mmu_far'])->toBe('NR93');
});

it('keeps sources apart', function () {
    hop('HUB01A>AAA01A>TGT01A');
    SiteRoute::create([
        'source' => 'other-zone',
        'hub_code' => 'HUB09A', 'parent_code' => 'HUB09A', 'child_code' => 'TGT01A',
        'child_name' => 'elsewhere', 'level' => 1, 'sheet_row' => 1,
        'path' => 'HUB09A>TGT01A',
    ]);

    expect((new SiteRouteMap('test'))->routesTo('TGT01A'))->toHaveCount(1);
    expect((new SiteRouteMap())->routesTo('TGT01A'))->toHaveCount(2);   // no filter
});

it('matches codes regardless of case or padding', function () {
    hop('HUB01A>AAA01A>TGT01A');

    expect((new SiteRouteMap('test'))->routesTo(' tgt01a '))->toHaveCount(1);
});
