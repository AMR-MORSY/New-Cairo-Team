<?php

use App\Enums\UserRoles;
use App\Livewire\Sites\Actions\ImportNewSites;
use App\Models\Site\Site;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->admin = User::factory()->role(UserRoles::SITE_ADMIN)->create();
});

function sitesCsv(array $rows): UploadedFile
{
    $lines = [implode(',', ImportNewSites::COLUMNS)];

    foreach ($rows as $row) {
        $lines[] = implode(',', array_map(fn ($c) => $row[$c] ?? '', ImportNewSites::COLUMNS));
    }

    return UploadedFile::fake()->createWithContent('sites.csv', implode("\n", $lines) . "\n");
}

function siteRow(string $code, array $extra = []): array
{
    return array_merge([
        'site_code' => $code, 'site_name' => "NAME-{$code}", 'zone' => 'CE',
        'type' => 'Macro', 'category' => 'Normal', 'severity' => 'Bronze',
    ], $extra);
}

it('sorts every row into ready, exists, duplicate or invalid', function () {
    Site::create(siteRow('OLD01CA') + ['team' => 'HGLI', 'sharing' => 'No', 'status' => 'On Air']);

    $component = Livewire::actingAs($this->admin)
        ->test(ImportNewSites::class)
        ->set('file', sitesCsv([
            siteRow('NEW01CA'),
            siteRow('new02ca', ['zone' => 'Cairo East']),     // lower case code, zone by name
            siteRow('OLD01CA'),                                 // already in the table
            siteRow('NEW01CA'),                                 // second time in the file
            siteRow('BAD01CA', ['type' => 'Spaceship']),
        ]));

    $statuses = array_column($component->get('rows'), 'status', 'line');

    expect($statuses)->toBe([2 => 'ready', 3 => 'ready', 4 => 'exists', 5 => 'duplicate', 6 => 'invalid']);
    expect($component->get('rows')[1]['data'])->toMatchArray(['site_code' => 'NEW02CA', 'zone' => 'CE', 'team' => 'HGLI']);
});

it('imports only the ready rows', function () {
    Livewire::actingAs($this->admin)
        ->test(ImportNewSites::class)
        ->set('file', sitesCsv([siteRow('NEW01CA'), siteRow('BAD01CA', ['severity' => 'Platinum'])]))
        ->call('import');

    expect(Site::where('site_code', 'NEW01CA')->exists())->toBeTrue();
    expect(Site::where('site_code', 'BAD01CA')->exists())->toBeFalse();

    $site = Site::where('site_code', 'NEW01CA')->first();
    expect($site->sharing)->toBe('No');
    expect($site->status)->toBe('On Air');
    expect($site->host)->toBeNull();
});

it('refuses a file without the required columns', function () {
    $file = UploadedFile::fake()->createWithContent('sites.csv', "code_of_site,label\nX,Y\n");

    Livewire::actingAs($this->admin)
        ->test(ImportNewSites::class)
        ->set('file', $file)
        ->assertHasErrors('file')
        ->assertSet('rows', []);
});

it('keeps people who cannot create sites out', function () {
    $engineer = User::factory()->create();

    Livewire::actingAs($engineer)
        ->test(ImportNewSites::class)
        ->assertForbidden();
});

it('renders the page for a site admin', function () {
    $this->actingAs($this->admin)
        ->get(route('site.import_new_sites'))
        ->assertOk()
        ->assertSee('Choose a file')
        ->assertSee('Download template');
});

it('ignores empty rows that Excel keeps below the data', function () {
    // one site, then rows that hold nothing but formatting - Excel reports these as part of the sheet
    $book = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $book->getActiveSheet();
    $sheet->fromArray([ImportNewSites::COLUMNS, array_map(fn ($c) => siteRow('ONE01CA')[$c] ?? '', ImportNewSites::COLUMNS)]);
    $sheet->getStyle('A3:T40')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFFF00');
    $sheet->setCellValue('C10', '   ');   // whitespace only

    $path = tempnam(sys_get_temp_dir(), 'sites') . '.xlsx';
    (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($book))->save($path);

    $component = Livewire::actingAs($this->admin)
        ->test(ImportNewSites::class)
        ->set('file', UploadedFile::fake()->createWithContent('sites.xlsx', file_get_contents($path)))
        ->assertHasNoErrors();

    expect($component->get('rows'))->toHaveCount(1);
    expect($component->get('rows')[0])->toMatchArray(['line' => 2, 'status' => 'ready']);
});

it('trims the site name and turns its spaces into dashes', function (string $typed, string $stored) {
    $component = Livewire::actingAs($this->admin)
        ->test(ImportNewSites::class)
        ->set('file', sitesCsv([siteRow('NEW01CA', ['site_name' => $typed])]));

    expect($component->get('rows')[0])->toMatchArray(['status' => 'ready']);
    expect($component->get('rows')[0]['data']['site_name'])->toBe($stored);
})->with([
    'inner spaces' => ['new ca wlid', 'NEW-CA-WLID'],
    'padded and doubled' => ['  NW  CA   SQ  ', 'NW-CA-SQ'],
    'spaced dash' => ['NW - CA', 'NW-CA'],
    'non-breaking space' => ["NW\u{00A0}CA", 'NW-CA'],
    'already dashed' => ['NW-CA_3', 'NW-CA_3'],
]);
