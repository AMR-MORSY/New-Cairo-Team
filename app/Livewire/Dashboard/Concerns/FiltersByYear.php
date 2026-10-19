<?php

namespace App\Livewire\Dashboard\Concerns;

use App\Models\Modification\Modification;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

/**
 * The year picked on the dashboard. Overview passes it down as the reactive
 * pageFilters prop, so changing it re-renders every widget that uses this.
 */
trait FiltersByYear
{
    use InteractsWithPageFilters;

    protected function year(): int
    {
        return (int) ($this->pageFilters['year'] ?? now()->year);
    }

    /**
     * Modifications requested in each month of $year, January first.
     *
     * Counted in PHP rather than with month(): the tests run on SQLite,
     * which has no month(), and a year is under two thousand rows.
     *
     * @return array<int, int>
     */
    protected function monthlyModificationCounts(int $year): array
    {
        $byMonth = Modification::query()
            ->where('year', $year)
            ->whereNotNull('request_date')
            ->pluck('request_date')
            // the model's request_date accessor returns the raw string, not a date
            ->countBy(fn ($date) => Carbon::parse($date)->month);

        return array_map(fn ($m) => (int) ($byMonth[$m] ?? 0), range(1, 12));
    }
}
