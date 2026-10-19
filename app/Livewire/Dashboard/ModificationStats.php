<?php

namespace App\Livewire\Dashboard;

use App\Enums\ModificationStatus;
use App\Livewire\Dashboard\Concerns\FiltersByYear;
use App\Models\Modification\Modification;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ModificationStats extends StatsOverviewWidget
{
    use FiltersByYear;

    protected int|array|null $columns = 3;

    protected function getStats(): array
    {
        $year = $this->year();
        $inYear = fn () => Modification::query()->where('year', $year);

        // per-month counts for the sparkline under "Requested"
        $spark = $this->monthlyModificationCounts($year);

        $requested = $inYear()->count();
        $notReported = $inYear()->where('reported', false)
            ->where('status', '!=', ModificationStatus::CANCELLED)->count();

        return [
            // open work is today's queue, whatever year it was requested in
            Stat::make(__('In progress'), number_format(Modification::where('status', ModificationStatus::IN_PROGRESS)->count()))
                ->description(__('all years'))
                ->color('warning'),

            Stat::make(__('Waiting D6'), number_format(Modification::where('status', ModificationStatus::WAITING_D6)->count()))
                ->description(__('all years'))
                ->color('info'),

            Stat::make(__('Requested in :year', ['year' => $year]), number_format($requested))
                ->chart($spark)
                ->color('success'),

            Stat::make(__('Done in :year', ['year' => $year]), number_format($inYear()->where('status', ModificationStatus::Done)->count()))
                ->color('success'),

            Stat::make(__('Not reported in :year', ['year' => $year]), number_format($notReported))
                ->description(__('of :n requested, cancelled excluded', ['n' => number_format($requested)]))
                ->color($notReported ? 'danger' : 'success'),

            Stat::make(__('Final cost in :year', ['year' => $year]), number_format((float) $inYear()->sum('final_cost')))
                ->description(__('estimated :n', ['n' => number_format((float) $inYear()->sum('est_cost'))])),
        ];
    }
}
