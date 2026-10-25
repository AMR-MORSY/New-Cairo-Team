<?php

namespace App\Livewire\Sites\Actions;

use App\Livewire\Forms\BatteryForm;
use App\Models\Site\Battery;
use App\Models\Site\Site;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
use Masmerise\Toaster\Toaster;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Bulk battery creation from a spreadsheet, the same flow as ImportNewSites:
 * upload, check every row, then import the rows that passed.
 *
 * Rows are checked with BatteryForm's own rules. A site may hold several
 * batteries, so "already exists" means an identical record is on file (same
 * site, brand, installation date, strings and category) - that is what
 * importing the same sheet twice would create.
 */
#[Title('Import New Batteries')]
class ImportNewBatteries extends Component
{
    use WithFileUploads;

    /** Rows past this are refused rather than silently cut off. */
    private const MAX_ROWS = 2000;

    /** The columns, in template order. Header matching ignores case and spaces. */
    public const COLUMNS = [
        'site_code', 'batteries_brand', 'no_strings', 'category', 'installation_date',
        'battery_volt', 'battery_amp_hr', 'batteries_status', 'stock', 'theft_case', 'comment',
    ];

    /** Headers people actually type, mapped to the column. */
    private const ALIASES = [
        'code' => 'site_code', 'site' => 'site_code', 'brand' => 'batteries_brand',
        'strings' => 'no_strings', 'no_of_strings' => 'no_strings', 'status' => 'batteries_status',
        'volt' => 'battery_volt', 'voltage' => 'battery_volt', 'amp_hr' => 'battery_amp_hr', 'ah' => 'battery_amp_hr',
    ];

    /** The fields that make two battery records "the same". */
    private const IDENTITY = ['site_code', 'batteries_brand', 'installation_date', 'no_strings', 'category'];

    public $file = null;

    /** @var array<int, array{line: int, data: array, status: string, errors: array}> */
    public array $rows = [];

    /** all | ready | invalid | no_site | exists | duplicate */
    public string $show = 'all';

    public function mount()
    {
        $this->authorize('create', Site::class);
    }

    /** Parse as soon as a file is picked, so the user sees the check before committing. */
    public function updatedFile(): void
    {
        $this->authorize('create', Site::class);
        $this->rows = [];
        $this->show = 'all';

        $this->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        $sheet = Excel::toArray(new class implements WithHeadingRow {}, $this->file)[0] ?? [];
        $headers = array_keys($sheet[0] ?? []);

        $missing = array_diff(['site_code', 'batteries_brand', 'no_strings'], array_keys($this->mapHeaders($headers)));

        if ($missing) {
            $this->addError('file', 'Missing column(s): ' . implode(', ', $missing) . '. Download the template to see the expected header.');

            return;
        }

        // empty rows Excel keeps below the data; keys are kept so line numbers stay true
        $sheet = array_filter($sheet, fn ($raw) => ! $this->isBlank($raw));

        if (! $sheet) {
            $this->addError('file', 'The file has no rows under its header.');

            return;
        }

        if (count($sheet) > self::MAX_ROWS) {
            $this->addError('file', 'The file has ' . count($sheet) . ' rows; import at most ' . self::MAX_ROWS . ' at a time.');

            return;
        }

        $this->rows = $this->check($sheet);
    }

    public function import()
    {
        $this->authorize('create', Site::class);

        // re-check: the preview is client-held state and the table may have changed since
        $rows = $this->check(array_column($this->rows, 'data'), fromPreview: true);
        $ready = array_filter($rows, fn ($r) => $r['status'] === 'ready');

        if (! $ready) {
            $this->rows = $rows;
            Toaster::error('There are no valid batteries to import.');

            return;
        }

        DB::transaction(function () use ($ready) {
            foreach ($ready as $row) {
                // optional blanks are stored as NULL
                Battery::create(array_map(fn ($v) => $v === '' ? null : $v, $row['data']));
            }
        });

        Toaster::success(count($ready) . ' batteries imported');

        return redirect()->route('site.import_new_batteries');
    }

    public function clear(): void
    {
        $this->reset(['file', 'rows', 'show']);
        $this->resetErrorBag();
    }

    /** A CSV holding just the header and one example row. */
    public function downloadTemplate()
    {
        $example = [
            'site_code' => '1234CA', 'batteries_brand' => 'Narada', 'no_strings' => 2, 'category' => 'New',
            'installation_date' => now()->format('Y-m-d'), 'battery_volt' => '48', 'battery_amp_hr' => '150',
            'batteries_status' => '', 'stock' => '', 'theft_case' => '', 'comment' => '',
        ];

        return response()->streamDownload(function () use ($example) {
            $out = fopen('php://output', 'w');
            fputcsv($out, self::COLUMNS);
            fputcsv($out, array_map(fn ($c) => $example[$c], self::COLUMNS));
            fclose($out);
        }, 'new-batteries-template.csv', ['Content-Type' => 'text/csv']);
    }

    #[Computed]
    public function counts(): array
    {
        return array_count_values(array_column($this->rows, 'status')) + [
            'ready' => 0, 'invalid' => 0, 'no_site' => 0, 'exists' => 0, 'duplicate' => 0,
        ];
    }

    #[Computed]
    public function visibleRows(): array
    {
        return $this->show === 'all'
            ? $this->rows
            : array_values(array_filter($this->rows, fn ($r) => $r['status'] === $this->show));
    }

    /* ---------------------------------------------------------- checking --- */

    /**
     * @param array<int, array> $sheet raw rows keyed by header, or already-normalised preview data
     */
    private function check(array $sheet, bool $fromPreview = false): array
    {
        $sites = Site::query()->pluck('site_code')->map(fn ($c) => strtoupper($c))->flip();
        $rules = (new BatteryForm($this, 'form'))->rules();
        $seen = [];
        $checked = [];

        $data = [];
        foreach ($fromPreview ? array_values($sheet) : $sheet as $i => $raw) {
            $data[$i] = $fromPreview ? $raw : $this->normalise($raw);
        }

        $onFile = $this->recordsOnFile(array_unique(array_column($data, 'site_code')));

        foreach ($data as $i => $row) {
            $line = $fromPreview ? $this->rows[$i]['line'] : $i + 2;   // +1 header, +1 one-based
            $code = $row['site_code'];
            $identity = $this->identity($row);

            if ($code !== '' && ! isset($sites[$code])) {
                $checked[] = ['line' => $line, 'data' => $row, 'status' => 'no_site', 'errors' => ["No site {$code} in the sites table"]];

                continue;
            }

            if (isset($onFile[$identity])) {
                $checked[] = ['line' => $line, 'data' => $row, 'status' => 'exists', 'errors' => ['The same battery is already recorded for this site']];

                continue;
            }

            if (isset($seen[$identity])) {
                $checked[] = ['line' => $line, 'data' => $row, 'status' => 'duplicate', 'errors' => ["Same battery as line {$seen[$identity]}"]];

                continue;
            }

            $seen[$identity] = $line;

            $validator = Validator::make($row, $rules);

            $checked[] = [
                'line' => $line,
                'data' => $row,
                'status' => $validator->fails() ? 'invalid' : 'ready',
                'errors' => $validator->errors()->all(),
            ];
        }

        return $checked;
    }

    /** Identities of the batteries already saved on these sites. */
    private function recordsOnFile(array $codes): array
    {
        $codes = array_values(array_filter($codes));
        $onFile = [];

        foreach (array_chunk($codes, 1000) as $chunk) {
            Battery::query()->whereIn('site_code', $chunk)->get(self::IDENTITY)
                ->each(function ($b) use (&$onFile) {
                    $onFile[$this->identity([
                        'site_code' => strtoupper($b->site_code),
                        'batteries_brand' => (string) $b->batteries_brand,
                        'installation_date' => $b->installation_date ? Carbon::parse($b->installation_date)->format('Y-m-d') : '',
                        'no_strings' => (string) $b->no_strings,
                        'category' => (string) $b->category,
                    ])] = true;
                });
        }

        return $onFile;
    }

    private function identity(array $row): string
    {
        return strtolower(implode('|', array_map(fn ($f) => trim((string) ($row[$f] ?? '')), self::IDENTITY)));
    }

    /** True when none of the recognised columns holds anything but whitespace. */
    private function isBlank(array $raw): bool
    {
        foreach ($this->mapHeaders(array_keys($raw)) as $header) {
            if (trim((string) ($raw[$header] ?? '')) !== '') {
                return false;
            }
        }

        return true;
    }

    /** One spreadsheet row => the columns BatteryForm validates, tidied. */
    private function normalise(array $raw): array
    {
        $row = [];

        foreach ($this->mapHeaders(array_keys($raw)) as $column => $header) {
            $value = $raw[$header];
            $row[$column] = is_string($value) ? trim($value) : ($value ?? '');
        }

        $data = array_merge(array_fill_keys(self::COLUMNS, ''), $row);

        $data['site_code'] = strtoupper((string) $data['site_code']);

        // New / Tested / Used in any case; blank is New, the column's default
        $data['category'] = $data['category'] === '' ? 'New' : ucfirst(strtolower((string) $data['category']));

        foreach (['installation_date', 'theft_case'] as $date) {
            $data[$date] = $this->date($data[$date]);
        }

        if (is_numeric($data['no_strings'])) {
            $data['no_strings'] = (string) (int) $data['no_strings'];
        }

        // blanks stay '' here: the validator skips rules for '', as the create form relies on
        return array_map(fn ($v) => is_string($v) ? $v : (string) $v, $data);
    }

    /**
     * Excel keeps dates as day numbers (45231); a CSV has text. Either way the
     * result is Y-m-d, or the original text when it is not a date, so the
     * validator can say so.
     */
    private function date(mixed $value): string
    {
        if ($value === '' || $value === null) {
            return '';
        }

        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }

        try {
            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Throwable) {
            return (string) $value;
        }
    }

    /**
     * Sheet headers (already slugged by the reader: "Site Code" => site_code)
     * => column => header.
     *
     * @return array<string, string>
     */
    private function mapHeaders(array $headers): array
    {
        $byKey = [];
        foreach (self::COLUMNS as $column) {
            $byKey[strtolower($column)] = $column;
        }

        $map = [];
        foreach ($headers as $header) {
            $key = strtolower(str_replace([' ', '-'], '_', trim((string) $header)));
            $column = $byKey[$key] ?? $byKey[self::ALIASES[$key] ?? ''] ?? null;

            if ($column && ! isset($map[$column])) {
                $map[$column] = $header;
            }
        }

        return $map;
    }

    public function render()
    {
        return view('livewire.sites.actions.import-new-batteries');
    }
}
