<?php

namespace App\Livewire\Dashboard;

use App\Models\Modification\Modification;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Hosts the dashboard widgets and owns the year filter. The widgets receive
 * it as their reactive pageFilters prop, so they redraw when it changes.
 */
class Overview extends Component
{
    #[Url]
    public ?int $year = null;

    /** @var array<int, int> */
    public array $years = [];

    public function mount(): void
    {
        $this->years = Modification::query()->distinct()->orderByDesc('year')->pluck('year')
            ->map(fn ($y) => (int) $y)->all();

        if (! in_array($this->year, $this->years, true)) {
            $this->year = in_array(now()->year, $this->years, true) ? now()->year : ($this->years[0] ?? now()->year);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.overview', [
            'filters' => ['year' => $this->year],
        ]);
    }
}
