<?php

namespace App\Livewire\Dashboard;

use App\Enums\ModificationStatus;
use App\Livewire\Dashboard\Concerns\FiltersByYear;
use App\Models\Modification\Modification;
use Filament\Widgets\ChartWidget;

class ModificationStatusChart extends ChartWidget
{
    use FiltersByYear;

    protected ?string $maxHeight = '280px';

    /** one fixed colour per status, so a status looks the same every year */
    private const COLORS = [
        'Done' => '#22c55e',
        'In Progress' => '#ff7900',
        'Waiting D6' => '#3b82f6',
        'Cancelled' => '#9ca3af',
    ];

    public function getHeading(): string
    {
        return __('Status of :year requests', ['year' => $this->year()]);
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $counts = Modification::query()
            ->where('year', $this->year())
            ->selectRaw('status, count(*) n')
            ->groupBy('status')
            ->pluck('n', 'status');

        $statuses = array_column(ModificationStatus::cases(), 'value');

        return [
            'datasets' => [[
                'data' => array_map(fn ($s) => (int) ($counts[$s] ?? 0), $statuses),
                'backgroundColor' => array_map(fn ($s) => self::COLORS[$s] ?? '#6b7280', $statuses),
            ]],
            'labels' => $statuses,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => ['x' => ['display' => false], 'y' => ['display' => false]],
        ];
    }
}
