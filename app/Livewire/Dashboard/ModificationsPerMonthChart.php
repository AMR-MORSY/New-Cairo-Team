<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Dashboard\Concerns\FiltersByYear;
use Filament\Widgets\ChartWidget;

class ModificationsPerMonthChart extends ChartWidget
{
    use FiltersByYear;

    protected ?string $maxHeight = '280px';

    public function getHeading(): string
    {
        return __('Modifications per month');
    }

    public function getDescription(): string
    {
        return __(':year against :previous', ['year' => $this->year(), 'previous' => $this->year() - 1]);
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $year = $this->year();

        return [
            'datasets' => [
                $this->dataset((string) $year, $this->monthlyModificationCounts($year), '#ff7900'),
                $this->dataset((string) ($year - 1), $this->monthlyModificationCounts($year - 1), '#9ca3af'),
            ],
            'labels' => array_map(fn ($m) => now()->startOfYear()->month($m)->translatedFormat('M'), range(1, 12)),
        ];
    }

    private function dataset(string $label, array $data, string $color): array
    {
        return [
            'label' => $label,
            'data' => $data,
            'borderColor' => $color,
            'backgroundColor' => $color,
            'tension' => 0.3,
        ];
    }
}
