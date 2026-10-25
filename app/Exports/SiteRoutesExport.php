<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * The Routes tab of the CE VIP / TP / G.SQR pages as a sheet: one row per
 * battery of every site on a site's live routes. Rows are built by
 * SiteRoutesWithBatteries::exportRows(), from the same data the tab shows.
 */
class SiteRoutesExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public const HEADINGS = [
        'Site', 'Site name', 'Category', 'Routes',
        'Route site', 'Route site name', 'Brand', 'Strings', 'Battery category', 'Installed',
    ];

    public function __construct(private readonly array $rows) {}

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return self::HEADINGS;
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        // a site with several routes writes one per line in the cell
        $sheet->getStyle('D:D')->getAlignment()->setWrapText(true);

        return [1 => ['font' => ['bold' => true]]];
    }
}
