<?php

namespace App\Livewire\Dashboard;

use App\Models\Site\Site;
use Filament\Widgets\ChartWidget;

/** Not tied to the year filter: it is the network as it stands today. */
class SitesPerTeamChart extends ChartWidget
{
    protected ?string $maxHeight = '280px';

    public function getHeading(): string
    {
        return __('Sites per team');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $rows = Site::query()
            ->selectRaw("team, sum(status = 'On Air') on_air, sum(status <> 'On Air') off_air, count(*) total")
            ->groupBy('team')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('On Air'),
                    'data' => $rows->map(fn ($r) => (int) $r->on_air)->all(),
                    'backgroundColor' => '#ff7900',
                ],
                [
                    'label' => __('Off Air'),
                    'data' => $rows->map(fn ($r) => (int) $r->off_air)->all(),
                    'backgroundColor' => '#9ca3af',
                ],
            ],
            // raw query, so team is the stored string rather than the Teams enum
            'labels' => $rows->map(fn ($r) => $r->getRawOriginal('team') ?: __('No team'))->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => ['x' => ['stacked' => true], 'y' => ['stacked' => true]],
        ];
    }
}
