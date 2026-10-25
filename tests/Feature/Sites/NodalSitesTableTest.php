<?php

use App\Livewire\Tables\Site\NodalSitesTable;
use App\Models\Site\Site;
use App\Models\Site\SiteRoute;
use App\Models\User;
use Livewire\Livewire;

function nodalTableSite(string $code, string $zone = 'CE'): void
{
    Site::create([
        'site_code' => $code, 'site_name' => "NAME-{$code}", 'zone' => $zone, 'team' => $zone === 'CE' ? 'HGLI' : 'AGLI',
        'type' => 'Macro', 'category' => 'Normal', 'severity' => 'Bronze', 'sharing' => 'No', 'status' => 'On Air',
    ]);
}

function nodalTableHop(string $path, ?string $fills = null): void
{
    $chain = explode('>', $path);

    SiteRoute::create([
        'source' => 'test', 'hub_code' => $chain[0], 'hub_name' => 'NAME-' . $chain[0], 'parent_code' => $chain[count($chain) - 2],
        'child_code' => end($chain), 'child_name' => 'NAME-' . end($chain), 'level' => count($chain) - 1,
        'sheet_row' => 1, 'path' => $path, 'path_fills' => $fills,
    ]);
}

it('lists the nodals from site_routes, with their live cascades', function () {
    foreach (['HUB01A', 'PAR01A', 'LEAF1A', 'LEAF2A', 'RED01A', 'LEAF3A'] as $code) {
        nodalTableSite($code);
    }
    nodalTableSite('DN0001', 'DN');   // a nodal, but not in Cairo East

    nodalTableHop('HUB01A>PAR01A');
    nodalTableHop('HUB01A>PAR01A>LEAF1A');
    nodalTableHop('HUB01A>PAR01A>LEAF2A');
    nodalTableHop('HUB01A>RED01A');
    nodalTableHop('HUB01A>RED01A>LEAF3A', ',,red');   // only a red link below: not a nodal
    nodalTableHop('DN0001>LEAF1A');

    $table = Livewire::actingAs(User::factory()->create())
        ->test(NodalSitesTable::class, ['target' => 'nodals']);

    $table->assertSee('PAR01A')->assertSee('HUB01A')->assertDontSee('DN0001');

    $rows = collect($table->instance()->datasource()->get())->pluck('site_code')->all();
    expect($rows)->toEqualCanonicalizing(['HUB01A', 'PAR01A']);

    $fields = $table->instance()->fields()->fields;
    $par = $table->instance()->datasource()->where('site_code', 'PAR01A')->first();   // count_cascades comes from the query

    expect($fields['count_cascades']($par))->toBe(2);
});

it('sorts by the cascade count in SQL', function () {
    foreach (['HUB01A', 'BIG01A', 'SML01A', 'L1', 'L2', 'L3'] as $code) {
        nodalTableSite($code);
    }

    nodalTableHop('HUB01A>SML01A');
    nodalTableHop('HUB01A>BIG01A');
    nodalTableHop('HUB01A>SML01A>L1');
    nodalTableHop('HUB01A>BIG01A>L2');
    nodalTableHop('HUB01A>BIG01A>L3');

    Livewire::actingAs(User::factory()->create())
        ->test(NodalSitesTable::class, ['target' => 'nodals'])
        ->call('sortBy', 'count_cascades')
        ->assertHasNoErrors()
        ->assertSeeInOrder(['SML01A', 'BIG01A', 'HUB01A']);   // 1, 2, 5

    $table = new NodalSitesTable();
    $table->target = 'nodals';

    expect($table->datasource()->orderBy('count_cascades', 'desc')->pluck('count_cascades', 'site_code')->all())
        ->toBe(['HUB01A' => 5, 'BIG01A' => 2, 'SML01A' => 1]);
});

it('picks VIP and G.SQR sites by every category carrying the tag', function (string $target, array $expected) {
    $categories = [
        'V1' => 'VIP', 'V2' => 'TP + G.SQR + VIP', 'V3' => 'VIP + NDL + TP',
        'G1' => 'G.SQR', 'G2' => 'TP + G.SQR + NDL', 'N1' => 'NDL', 'P1' => 'Normal',
    ];

    foreach ($categories as $code => $category) {
        nodalTableSite($code);
        Site::where('site_code', $code)->update(['category' => $category]);
    }

    $table = new NodalSitesTable();
    $table->target = $target;

    expect($table->datasource()->pluck('site_code')->all())->toEqualCanonicalizing($expected);
})->with([
    'vip' => ['vip', ['V1', 'V2', 'V3']],
    'g.sqr' => ['GSquare', ['G1', 'G2', 'V2']],
]);
