<?php

namespace App\Livewire\Sites\Actions;

use App\Enums\Zones;
use App\Livewire\Forms\SiteForm;
use App\Models\Site\Site;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
use Masmerise\Toaster\Toaster;

/**
 * Bulk site creation from a spreadsheet: upload, check every row, then
 * import the rows that passed.
 *
 * Rows are checked with SiteForm's own rules, so an imported site has to
 * satisfy exactly what a hand-made one does. They are checked again on
 * import, because the preview lives in public component state.
 */
#[Title('Import New Sites')]
class ImportNewSites extends Component
{
    use WithFileUploads;

    /** Rows past this are refused rather than silently cut off. */
    private const MAX_ROWS = 2000;

    /** The columns, in template order. Header matching ignores case and spaces. */
    public const COLUMNS = [
        'site_code', 'site_name', 'zone', 'team', 'type', 'category', 'severity',
        'sharing', 'host', 'gest', 'status', 'BSC', 'RNC', 'office',
        'vf_code', 'et_code', 'we_code', 'cells_2G', 'cells_3G', 'cells_4G',
    ];

    /** Headers people actually type, mapped to the column. */
    private const ALIASES = ['guest' => 'gest', 'code' => 'site_code', 'name' => 'site_name'];

    public $file = null;

    /** @var array<int, array{line: int, data: array, status: string, errors: array}> */
    public array $rows = [];

    /** all | ready | exists | duplicate | invalid */
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

        $missing = array_diff(['site_code', 'site_name', 'zone'], array_keys($this->mapHeaders($headers)));

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
            Toaster::error('There are no valid new sites to import.');

            return;
        }

        DB::transaction(function () use ($ready) {
            foreach ($ready as $row) {
                // optional blanks are stored as NULL, as the create form does
                Site::create(array_map(fn ($v) => $v === '' ? null : $v, $row['data']));
            }
        });

        Toaster::success(count($ready) . ' sites imported');

        return redirect()->route('site.import_new_sites');
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
            'site_code' => '1234CA', 'site_name' => 'NEW-SITE-NAME', 'zone' => 'CE', 'team' => 'HGLI',
            'type' => 'Macro', 'category' => 'Normal', 'severity' => 'Bronze', 'sharing' => 'No',
            'host' => '', 'gest' => '', 'status' => 'On Air', 'BSC' => '', 'RNC' => '', 'office' => '',
            'vf_code' => '', 'et_code' => '', 'we_code' => '', 'cells_2G' => 0, 'cells_3G' => 0, 'cells_4G' => 0,
        ];

        return response()->streamDownload(function () use ($example) {
            $out = fopen('php://output', 'w');
            fputcsv($out, self::COLUMNS);
            fputcsv($out, array_map(fn ($c) => $example[$c], self::COLUMNS));
            fclose($out);
        }, 'new-sites-template.csv', ['Content-Type' => 'text/csv']);
    }

    #[Computed]
    public function counts(): array
    {
        return array_count_values(array_column($this->rows, 'status')) + [
            'ready' => 0, 'exists' => 0, 'duplicate' => 0, 'invalid' => 0,
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
        $existing = Site::query()->pluck('site_code')->map(fn ($c) => strtoupper($c))->flip();
        $form = new SiteForm($this, 'form');
        $seen = [];
        $checked = [];

        /*
         * Blank rows are dropped by the caller, on the sheet's own values:
         * after normalise() fills in defaults (sharing No, status On Air,
         * cells 0) no row would look empty any more. Sheet keys survive that
         * filter, so the key still gives the line number.
         */
        foreach ($fromPreview ? array_values($sheet) : $sheet as $i => $raw) {
            $data = $fromPreview ? $raw : $this->normalise($raw);
            $line = $fromPreview ? $this->rows[$i]['line'] : $i + 2;   // +1 header, +1 one-based

            $code = $data['site_code'];

            if ($code !== '' && isset($existing[$code])) {
                $checked[] = ['line' => $line, 'data' => $data, 'status' => 'exists', 'errors' => ['Already in the sites table']];

                continue;
            }

            if ($code !== '' && isset($seen[$code])) {
                $checked[] = ['line' => $line, 'data' => $data, 'status' => 'duplicate', 'errors' => ["Same code as line {$seen[$code]}"]];

                continue;
            }

            $seen[$code] = $line;

            // SiteForm's closures read host / gest / team off the form, so load the row into it
            $form->reset();
            $form->fill($data);

            $validator = Validator::make($data, $form->rules());

            $checked[] = [
                'line' => $line,
                'data' => $data,
                'status' => $validator->fails() ? 'invalid' : 'ready',
                'errors' => $validator->errors()->all(),
            ];
        }

        return $checked;
    }

    /**
     * Site names may not contain spaces, so they are turned into dashes here
     * rather than sent back to the user: "NEW CA  WLID" -> "NEW-CA-WLID".
     * A run of spaces, with any dashes around it, becomes one dash, so
     * "NW - CA" is "NW-CA", not "NW---CA". Excel's non-breaking space counts.
     */
    private function dashSpaces(string $name): string
    {
        $name = preg_replace('/^[\s\x{00A0}]+|[\s\x{00A0}]+$/u', '', $name);

        return preg_replace('/[\s\x{00A0}-]*[\s\x{00A0}][\s\x{00A0}-]*/u', '-', $name);
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

    /** One spreadsheet row => the columns SiteForm validates, tidied. */
    private function normalise(array $raw): array
    {
        $row = [];

        foreach ($this->mapHeaders(array_keys($raw)) as $column => $header) {
            $value = $raw[$header];
            $row[$column] = is_string($value) ? trim($value) : ($value ?? '');
        }

        $data = array_merge(array_fill_keys(self::COLUMNS, ''), $row);

        $data['site_code'] = strtoupper((string) $data['site_code']);
        $data['site_name'] = strtoupper($this->dashSpaces((string) $data['site_name']));

        // zone may be written as its code (CE) or its name (Cairo East)
        $zone = Zones::tryFrom(strtoupper((string) $data['zone']))
            ?? collect(Zones::cases())->first(fn (Zones $z) => strcasecmp($z->label(), (string) $data['zone']) === 0);

        if ($zone) {
            $data['zone'] = $zone->value;
            $data['team'] = $data['team'] === '' ? $zone->team()->value : strtoupper((string) $data['team']);
        }

        $data['sharing'] = $data['sharing'] === '' ? 'No' : ucfirst(strtolower((string) $data['sharing']));
        $data['status'] = $data['status'] === '' ? 'On Air' : $data['status'];

        foreach (['cells_2G', 'cells_3G', 'cells_4G'] as $cells) {
            $data[$cells] = $data[$cells] === '' ? '0' : (string) (int) $data[$cells];
        }

        // blanks stay '' here: the validator skips rules for '' but runs the enum rules on null
        return array_map(fn ($v) => is_string($v) ? $v : (string) $v, $data);
    }

    /**
     * Sheet headers (already slugged by the reader: "Site Code" => site_code,
     * "BSC" => bsc) => column => header.
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
        return view('livewire.sites.actions.import-new-sites');
    }
}
