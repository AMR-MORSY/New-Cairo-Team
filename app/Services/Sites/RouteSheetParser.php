<?php

namespace App\Services\Sites;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use RuntimeException;

/**
 * Reads a transmission planning sheet (e.g. "New Cairo MMUID.xlsx") into a
 * flat list of parent -> child hops.
 *
 * The sheet is a tree drawn with indentation rather than a table: column A is
 * the hub, and each column to the right is one level further down the chain.
 * A parent is written once and its cell is merged down over its children, so
 * a child's parent is simply the nearest filled cell above it in the previous
 * site column.
 *
 *      A          B          C          D      E    F     G
 *      hub        level 1    level 2    lvl3   mmu  mmu   level 4
 *
 * The narrow column pairs (E/F, H/I, K/L) hold the two MMU ids of the hop
 * that reaches the site named to their right.
 *
 * Cell fill colour is read as well: the planners colour cells red, yellow or
 * green, and losing that would lose most of what makes the sheet readable.
 */
class RouteSheetParser
{
    /**
     * How many columns to look at when working out the layout. Well past the
     * widest sheet seen; empty columns cost nothing.
     */
    private const SCAN_COLS = 30;

    /** An MMU id: a short run of letters and digits, e.g. NR83, NI9E. */
    private const MMU_ID = '/^[A-Z0-9]{3,5}$/i';

    /**
     * Columns that name a site, in depth order (0-based), worked out from the
     * sheet itself rather than assumed.
     *
     * @var array<int, int>
     */
    private array $siteCols = [];

    /**
     * Site column => [near MMU column, far MMU column].
     *
     * @var array<int, array{0: int, 1: int}>
     */
    private array $mmuCols = [];

    /**
     * A site code is four alphanumerics followed by two area letters, e.g.
     * 0616CA, 7WDYCA, 0005UP. The digit check keeps plain six-letter words
     * such as MARKAZ and MADINA, which appear as stray labels, from matching.
     */
    private const CODE = '/\b([0-9A-Z]{4}[A-Z]{2})\b/i';

    /** ARGB the planners actually use, mapped to names the UI can style. */
    private const FILLS = [
        'FFFF0000' => 'red',
        'FFFFFF00' => 'yellow',
        'FF92D050' => 'green',
    ];

    /** @var array<int, array<int, string>> row => col => value */
    private array $grid = [];

    /** @var array<int, array<int, string>> row => col => fill name */
    private array $fills = [];

    private ?string $loaded = null;

    /**
     * @return array<int, array<string, mixed>> one entry per parent -> child hop
     */
    public function parse(string $path): array
    {
        $this->read($path);

        $hops = [];
        $maxRow = $this->grid ? max(array_keys($this->grid)) : -1;

        for ($row = 0; $row <= $maxRow; $row++) {
            foreach ($this->siteCols as $depth => $col) {
                if ($depth === 0) {
                    continue;   // the hub is a parent, never a child
                }

                $child = $this->site($this->grid[$row][$col] ?? null);

                if (! $child) {
                    continue;
                }

                $cells = $this->chainCells($row, $depth);
                $chain = $cells['codes'];

                if (count($chain) < 2) {
                    continue;   // no readable parent, so no route to record
                }

                [$near, $far] = $this->mmu($row, $col);

                [$hubCell] = $this->nearestAbove($row, $this->siteCols[0]);
                $hub = $this->site($hubCell);

                $hops[] = [
                    'hub_code' => $chain[0],
                    'hub_name' => $hub['name'] ?? null,
                    'parent_code' => $chain[count($chain) - 2],
                    'child_code' => $child['code'],
                    'child_name' => $child['name'],
                    'level' => $depth,
                    'sheet_row' => $row + 1,
                    'path' => implode('>', $chain),
                    // one entry per column of path, blank where the sheet left it uncoloured
                    'path_fills' => implode(',', array_map(fn ($f) => (string) $f, $cells['fills'])),
                    // the wording of each cell as drawn; variants differ per row
                    'path_names' => json_encode($cells['names']),
                    'mmu_near' => $near,
                    'mmu_far' => $far,
                    'fill' => $cells['fills'][$depth] ?? null,
                    'raw_label' => $child['raw'],
                ];
            }
        }

        return $hops;
    }

    /** Cells the parser could not read a site code out of, for reporting. */
    public function unparsed(string $path): array
    {
        $this->read($path);

        $bad = [];

        foreach ($this->grid as $row => $cells) {
            foreach ($this->siteCols as $col) {
                $v = $cells[$col] ?? null;

                if ($v && ! $this->site($v)) {
                    $bad[] = ['row' => $row + 1, 'col' => $col, 'value' => $v];
                }
            }
        }

        return $bad;
    }

    private function read(string $path): void
    {
        if ($this->loaded === $path) {
            return;
        }

        if (! is_readable($path)) {
            throw new RuntimeException("Cannot read sheet: {$path}");
        }

        $sheet = IOFactory::createReader('Xlsx')->load($path)->getActiveSheet();

        $this->grid = [];
        $this->fills = [];

        $lastRow = $sheet->getHighestRow();

        // every column, because which ones matter is decided from the content
        for ($row = 1; $row <= $lastRow; $row++) {
            for ($col = 0; $col < self::SCAN_COLS; $col++) {
                $cell = $sheet->getCell([$col + 1, $row]);
                $value = preg_replace('/\s+/', ' ', trim((string) $cell->getValue()));

                if ($value === '') {
                    continue;
                }

                $this->grid[$row - 1][$col] = $value;

                $fill = $cell->getStyle()->getFill();

                if ($fill->getFillType() !== Fill::FILL_NONE) {
                    $argb = (string) $fill->getStartColor()->getARGB();

                    if (isset(self::FILLS[$argb])) {
                        $this->fills[$row - 1][$col] = self::FILLS[$argb];
                    }
                }
            }
        }

        $this->detectLayout();

        $this->loaded = $path;
    }

    /**
     * Work out which columns name sites and which hold MMU ids.
     *
     * The positions were hard-coded to begin with, which only held for one
     * version of one sheet: inserting a column, adding a deeper cascade level
     * or opening another zone's workbook would have shifted everything and
     * quietly dropped rows. The two kinds of column are easy to tell apart by
     * what is in them -- a site cell carries a code like 0616CA, an MMU cell a
     * short id like NR83 -- so they are classified rather than assumed.
     */
    private function detectLayout(): void
    {
        $roles = [];

        for ($col = 0; $col < self::SCAN_COLS; $col++) {
            $sites = 0;
            $ids = 0;

            foreach ($this->grid as $cells) {
                $value = $cells[$col] ?? null;

                if ($value === null) {
                    continue;
                }

                if ($this->site($value)) {
                    $sites++;
                } elseif (preg_match(self::MMU_ID, $value)) {
                    $ids++;
                }
            }

            if ($sites > 0 && $sites >= $ids) {
                $roles[$col] = 'site';
            } elseif ($ids > 0) {
                $roles[$col] = 'mmu';
            }
        }

        $this->siteCols = array_values(array_keys($roles, 'site', true));

        if (count($this->siteCols) < 2) {
            throw new RuntimeException(
                'Could not find the site columns in this sheet. Expected a hub '
                . 'column and at least one cascade column holding codes like 0616CA.'
            );
        }

        // an MMU pair belongs to the site column it sits to the left of
        $this->mmuCols = [];

        foreach ($this->siteCols as $siteCol) {
            $pair = [];

            for ($col = $siteCol - 1; $col >= 0 && ($roles[$col] ?? null) === 'mmu'; $col--) {
                array_unshift($pair, $col);
            }

            if (count($pair) >= 2) {
                $this->mmuCols[$siteCol] = [$pair[0], $pair[1]];
            }
        }
    }

    /**
     * The layout the parser settled on, so a change to the sheet is visible
     * rather than silent.
     *
     * @return array{site_cols: array<int, int>, mmu_cols: array<int, array{0: int, 1: int}>}
     */
    public function layout(string $path): array
    {
        $this->read($path);

        return ['site_cols' => $this->siteCols, 'mmu_cols' => $this->mmuCols];
    }

    /** @return array{code: string, name: string, raw: string}|null */
    private function site(?string $cell): ?array
    {
        if (! $cell || ! preg_match(self::CODE, $cell, $m) || ! preg_match('/\d/', $m[1])) {
            return null;
        }

        $name = trim(preg_replace('/^[\s\-–—_]+/u', '', str_replace($m[0], '', $cell)));

        return ['code' => strtoupper($m[1]), 'name' => $name, 'raw' => $cell];
    }

    /**
     * The codes from the hub across to $depth on this row: the route exactly
     * as the sheet draws it, read right from column A.
     *
     * @return array<int, string>
     */
    private function chain(int $row, int $depth): array
    {
        return $this->chainCells($row, $depth)['codes'];
    }

    /**
     * The chain plus the colour of each cell in it.
     *
     * The fill has to travel with the chain: an inherited cell is blank on
     * this row and shows the colour of the merged cell above it, so reading
     * a colour off any other row that happens to name the same site gives
     * the wrong answer. 4167CA is drawn uncoloured on one row and red on
     * another, and guessing turned the uncoloured one red.
     *
     * @return array{codes: array<int, string>, fills: array<int, ?string>, names: array<int, string>}
     */
    private function chainCells(int $row, int $depth): array
    {
        $codes = [];
        $fills = [];
        $names = [];

        for ($d = 0; $d <= $depth; $d++) {
            $col = $this->siteCols[$d];

            if ($d === $depth) {
                $cell = $this->grid[$row][$col] ?? null;
                $from = $row;
            } else {
                [$cell, $from] = $this->nearestAbove($row, $col);
            }

            $site = $this->site($cell);

            if (! $site) {
                // a gap in the middle means the chain cannot be trusted
                return $d === $depth
                    ? ['codes' => $codes, 'fills' => $fills, 'names' => $names]
                    : ['codes' => [], 'fills' => [], 'names' => []];
            }

            $codes[] = $site['code'];
            $fills[] = $from === null ? null : ($this->fills[$from][$col] ?? null);
            $names[] = $site['name'];
        }

        return ['codes' => $codes, 'fills' => $fills, 'names' => $names];
    }

    /**
     * A merged parent cell carries its value only in the top-left cell, which
     * always sits at or above every child it covers. The row it was found on
     * is returned too, because that is where its colour lives.
     *
     * @return array{0: ?string, 1: ?int}
     */
    private function nearestAbove(int $row, int $col): array
    {
        for ($i = $row; $i >= 0; $i--) {
            if (! empty($this->grid[$i][$col])) {
                return [$this->grid[$i][$col], $i];
            }
        }

        return [null, null];
    }

    /** @return array{0: ?string, 1: ?string} */
    private function mmu(int $row, int $col): array
    {
        if (! isset($this->mmuCols[$col])) {
            return [null, null];
        }

        [$near, $far] = $this->mmuCols[$col];

        return [$this->grid[$row][$near] ?? null, $this->grid[$row][$far] ?? null];
    }
}
