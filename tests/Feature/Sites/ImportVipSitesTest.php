<?php

use App\Models\Site\Site;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

beforeEach(fn () => Storage::fake('local'));

function vipSite(string $code, string $category): Site
{
    return Site::create([
        'site_code' => $code, 'site_name' => "V-{$code}", 'zone' => 'CE', 'team' => 'HGLI',
        'type' => 'Macro', 'category' => $category, 'severity' => 'Bronze', 'sharing' => 'No', 'status' => 'On Air',
    ]);
}

function vipList(array $codes): string
{
    $book = new Spreadsheet();
    $book->getActiveSheet()->fromArray(array_merge(
        [['BTS Name', 'Site Code', 'Ranking']],
        array_map(fn ($c, $i) => ["BTS_{$c}", $c, $i + 1], $codes, array_keys($codes)),
    ));

    $path = tempnam(sys_get_temp_dir(), 'vip') . '.xlsx';
    (new Xlsx($book))->save($path);

    return $path;
}

it('resets VIP on every site and gives it back to the listed ones', function () {
    vipSite('KEEP1A', 'VIP + NDL');     // listed, already VIP: unchanged
    vipSite('GAIN1A', 'TP + NDL');      // listed: gains VIP
    vipSite('LOSE1A', 'TP + VIP');      // not listed: loses VIP
    vipSite('LOSE2A', 'VIP');
    vipSite('PLAIN1', 'Normal');        // not listed, never VIP
    vipSite('BSC01A', 'BSC');           // listed, but BSC has no VIP variant

    $this->artisan('sites:import-vip', ['path' => vipList(['keep1a', ' GAIN1A ', 'BSC01A', 'GHOST1'])])
        ->expectsOutputToContain('GHOST1')
        ->assertSuccessful();

    expect(Site::pluck('category', 'site_code')->all())->toMatchArray([
        'KEEP1A' => 'VIP + NDL',
        'GAIN1A' => 'VIP + NDL + TP',
        'LOSE1A' => 'TP',
        'LOSE2A' => 'Normal',
        'PLAIN1' => 'Normal',
        'BSC01A' => 'BSC',
    ]);
});

it('changes nothing on a dry run', function () {
    vipSite('LOSE1A', 'VIP');
    vipSite('GAIN1A', 'Normal');

    $this->artisan('sites:import-vip', ['path' => vipList(['GAIN1A']), '--dry-run' => true])->assertSuccessful();

    expect(Site::pluck('category', 'site_code')->all())->toMatchArray(['LOSE1A' => 'VIP', 'GAIN1A' => 'Normal']);
});

it('refuses a list where no code matches a site, rather than stripping VIP from all', function () {
    vipSite('LOSE1A', 'VIP');

    $this->artisan('sites:import-vip', ['path' => vipList(['GHOST1', 'GHOST2'])])->assertFailed();

    expect(Site::where('site_code', 'LOSE1A')->value('category'))->toBe('VIP');
});

it('refuses a sheet without the site code column', function () {
    $book = new Spreadsheet();
    $book->getActiveSheet()->fromArray([['Code'], ['GAIN1A']]);
    $path = tempnam(sys_get_temp_dir(), 'vip') . '.xlsx';
    (new Xlsx($book))->save($path);

    $this->artisan('sites:import-vip', ['path' => $path])
        ->expectsOutputToContain('--column')
        ->assertFailed();
});
