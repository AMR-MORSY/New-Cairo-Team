<?php

namespace App\Livewire\Dashboard;

use App\Enums\Teams;
use App\Models\Site\Site;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

/**
 * HGLI's VIP sites per zone. Not tied to the year filter: it is the network
 * as it stands today.
 *
 * A combined category counts as VIP: "VIP + NDL" (and any other spelling,
 * such as "VIP+NDL") is matched on the word, not on the whole value.
 */
class HgliVipSitesPerZoneChart extends ChartWidget
{
    protected ?string $maxHeight = '280px';

    private ?Collection $rows = null;

    public function getHeading(): string
    {
        return __('VIP sites per zone');
    }

    public function getDescription(): string
    {
        return __(':team, VIP + NDL included', ['team' => Teams::HGLI->value]);
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $rows = $this->rows();

        return [
            'datasets' => [[
                'label' => __('VIP sites'),
                'data' => $rows->map(fn ($r) => (int) $r->total)->all(),
                'backgroundColor' => '#ff7900',
            ]],
            // raw query, so zone is the stored string rather than the Zones enum
            'labels' => $rows->map(fn ($r) => $r->getRawOriginal('zone') ?: __('No zone'))->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
                // the total drawn on the canvas, above the bars
                'title' => [
                    'display' => true,
                    'text' => __('Total: :n', ['n' => number_format($this->rows()->sum('total'))]),
                    'font' => ['size' => 16, 'weight' => 'bold'],
                ],
            ],
            'scales' => ['y' => ['ticks' => ['precision' => 0]]],
        ];
    }

    /** Count per zone, largest first; cached because data and options both use it. */
    private function rows(): Collection
    {
        return $this->rows ??= Site::query()
            ->where('team', Teams::HGLI->value)
            ->where('category', 'like', '%VIP%')
            ->selectRaw('zone, count(*) total')
            ->groupBy('zone')
            ->orderByDesc('total')
            ->get();
    }
}
