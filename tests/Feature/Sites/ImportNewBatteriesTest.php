<?php

use App\Enums\UserRoles;
use App\Livewire\Sites\Actions\ImportNewBatteries;
use App\Models\Site\Battery;
use App\Models\Site\Site;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->admin = User::factory()->role(UserRoles::SITE_ADMIN)->create();

    foreach (['SITE1A', 'SITE2A'] as $code) {
        Site::create([
            'site_code' => $code, 'site_name' => "S-{$code}", 'zone' => 'CE', 'team' => 'HGLI', 'type' => 'Macro',
            'category' => 'Normal', 'severity' => 'Bronze', 'sharing' => 'No', 'status' => 'On Air',
        ]);
    }
});

function batteryRow(string $code, array $extra = []): array
{
    return array_merge([
        'site_code' => $code, 'batteries_brand' => 'Narada', 'no_strings' => 2,
        'category' => 'New', 'installation_date' => '2026-03-15',
    ], $extra);
}

/** An .xlsx, so Excel date cells are exercised; a 'date:' prefix writes a real date cell. */
function batteriesXlsx(array $rows): UploadedFile
{
    $book = new Spreadsheet();
    $sheet = $book->getActiveSheet();
    $sheet->fromArray([ImportNewBatteries::COLUMNS]);

    foreach ($rows as $r => $row) {
        foreach (ImportNewBatteries::COLUMNS as $c => $column) {
            $value = $row[$column] ?? null;

            if (is_string($value) && str_starts_with($value, 'date:')) {
                $value = ExcelDate::PHPToExcel(new DateTime(substr($value, 5)));
            }

            $sheet->setCellValue([$c + 1, $r + 2], $value);
        }
    }

    $path = tempnam(sys_get_temp_dir(), 'bat') . '.xlsx';
    (new Xlsx($book))->save($path);

    return UploadedFile::fake()->createWithContent('batteries.xlsx', file_get_contents($path));
}

it('sorts every row into ready, invalid, no site, exists or duplicate', function () {
    Battery::create(batteryRow('SITE2A'));

    $component = Livewire::actingAs($this->admin)
        ->test(ImportNewBatteries::class)
        ->set('file', batteriesXlsx([
            batteryRow('site1a', ['installation_date' => 'date:2026-01-20', 'category' => 'used']),
            batteryRow('SITE1A', ['no_strings' => 4]),              // second battery on the same site: fine
            batteryRow('GHOST1'),                                   // no such site
            batteryRow('SITE2A'),                                   // already recorded
            batteryRow('SITE1A', ['no_strings' => 4]),              // same as line 3
            batteryRow('SITE1A', ['no_strings' => 0]),              // below the minimum
        ]));

    expect(array_column($component->get('rows'), 'status', 'line'))
        ->toBe([2 => 'ready', 3 => 'ready', 4 => 'no_site', 5 => 'exists', 6 => 'duplicate', 7 => 'invalid']);

    expect($component->get('rows')[0]['data'])
        ->toMatchArray(['site_code' => 'SITE1A', 'installation_date' => '2026-01-20', 'category' => 'Used']);
});

it('imports only the ready rows', function () {
    Livewire::actingAs($this->admin)
        ->test(ImportNewBatteries::class)
        ->set('file', batteriesXlsx([batteryRow('SITE1A'), batteryRow('GHOST1')]))
        ->call('import');

    expect(Battery::count())->toBe(1);
    expect(Battery::first())->site_code->toBe('SITE1A')->stock->toBeNull();
});

it('adds nothing when the same file is imported twice', function () {
    $file = fn () => batteriesXlsx([batteryRow('SITE1A')]);

    Livewire::actingAs($this->admin)->test(ImportNewBatteries::class)->set('file', $file())->call('import');
    $second = Livewire::actingAs($this->admin)->test(ImportNewBatteries::class)->set('file', $file());

    expect($second->get('rows')[0]['status'])->toBe('exists');
    expect(Battery::count())->toBe(1);
});

it('refuses a file without the required columns', function () {
    Livewire::actingAs($this->admin)
        ->test(ImportNewBatteries::class)
        ->set('file', UploadedFile::fake()->createWithContent('b.csv', "site_code,label\nSITE1A,x\n"))
        ->assertHasErrors('file')
        ->assertSet('rows', []);
});

it('keeps people who cannot create sites out', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ImportNewBatteries::class)
        ->assertForbidden();
});

it('renders the page for a site admin', function () {
    $this->actingAs($this->admin)
        ->get(route('site.import_new_batteries'))
        ->assertOk()
        ->assertSee('Import New Batteries')
        ->assertSee('Download template');
});
