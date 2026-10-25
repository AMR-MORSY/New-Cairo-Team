<?php

use App\Models\Site\Site;
use App\Models\Site\SiteRoute;

function nodalSite(string $code, string $category): Site
{
    return Site::create([
        'site_code' => $code, 'site_name' => "N-{$code}", 'zone' => 'CE', 'team' => 'HGLI',
        'type' => 'Macro', 'category' => $category, 'severity' => 'Bronze', 'sharing' => 'No', 'status' => 'On Air',
    ]);
}

function nodalHop(string $path, string $fills = ''): void
{
    $chain = explode('>', $path);

    SiteRoute::create([
        'source' => 'test', 'hub_code' => $chain[0], 'parent_code' => $chain[count($chain) - 2],
        'child_code' => end($chain), 'child_name' => 'x', 'level' => count($chain) - 1, 'sheet_row' => 1,
        'path' => $path, 'path_fills' => $fills ?: null,
    ]);
}

it('adds NDL to parents and removes it from sites that stopped being one', function () {
    nodalSite('HUB01A', 'NDL');          // still a parent: keeps NDL
    nodalSite('PAR01A', 'TP');           // newly a parent: gains NDL
    nodalSite('OLD01A', 'TP + NDL');     // drawn, but nothing under it any more: loses NDL
    nodalSite('RED01A', 'VIP + NDL');    // only a red link under it: loses NDL
    nodalSite('LEAF1A', 'Normal');
    nodalSite('LEAF2A', 'Normal');
    nodalSite('ELSE1A', 'TP + NDL');     // in no sheet at all: not a parent, so loses NDL

    nodalHop('HUB01A>PAR01A');
    nodalHop('HUB01A>PAR01A>LEAF1A');
    nodalHop('HUB01A>OLD01A');
    nodalHop('HUB01A>RED01A');
    nodalHop('HUB01A>RED01A>LEAF2A', ',,red');

    $this->artisan('update:sites-nodality')->assertSuccessful();

    expect(Site::pluck('category', 'site_code')->all())->toMatchArray([
        'HUB01A' => 'NDL',
        'PAR01A' => 'TP + NDL',
        'OLD01A' => 'TP',
        'RED01A' => 'VIP',
        'LEAF1A' => 'Normal',
        'LEAF2A' => 'Normal',
        'ELSE1A' => 'TP',
    ]);
});

it('changes nothing on a dry run', function () {
    nodalSite('HUB01A', 'Normal');
    nodalSite('LEAF1A', 'Normal');
    nodalHop('HUB01A>LEAF1A');

    $this->artisan('update:sites-nodality --dry-run')->assertSuccessful();

    expect(Site::where('site_code', 'HUB01A')->value('category'))->toBe('Normal');
});

it('refuses to run with no routes imported', function () {
    nodalSite('HUB01A', 'NDL');

    $this->artisan('update:sites-nodality')->assertFailed();

    expect(Site::where('site_code', 'HUB01A')->value('category'))->toBe('NDL');
});
