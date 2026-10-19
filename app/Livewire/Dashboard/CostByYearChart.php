<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Dashboard\Concerns\FiltersByYear;
use App\Models\Modification\Modification;
use Filament\Widgets\ChartWidget;

/**
 * Every year side by side - the year filter only highlights the chosen one,
 * since the point of the chart is the comparison.
 */
class CostByYearChart extends ChartWidget
{
    use FiltersByYear;

    protected ?string $maxHeight = '280px';

    public function getHeading(): string
    {
        return __('Estimated vs final cost');
    }

    public function getDescription(): string
    {
        return __('by request year');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $rows = Modification::query()
            ->selectRaw('year, sum(est_cost) est, sum(final_cost) final')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $chosen = $this->year();

        // the selected year at full strength, the others faded
        $shade = fn (string $color) => $rows->map(fn ($r) => (int) $r->year === $chosen ? $color : $color . '66')->all();

        return [
            'datasets' => [
                [
                    'label' => __('Estimated'),
                    'data' => $rows->map(fn ($r) => round((float) $r->est))->all(),
                    'backgroundColor' => $shade('#9ca3af'),
                ],
                [
                    'label' => __('Final'),
                    'data' => $rows->map(fn ($r) => round((float) $r->final))->all(),
                    'backgroundColor' => $shade('#ff7900'),
                ],
            ],
            'labels' => $rows->pluck('year')->map(fn ($y) => (string) $y)->all(),
        ];
    }
}
