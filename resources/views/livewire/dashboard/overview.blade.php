<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <x-ui.heading size="xl" level="1">{{ __('Dashboard') }}</x-ui.heading>
            <x-ui.text class="mt-1">{{ __('Modifications and sites at a glance') }}</x-ui.text>
        </div>

        <label class="flex items-center gap-2">
            <span class="text-sm font-medium">{{ __('Year') }}</span>
            <select wire:model.live="year" class="select select-sm w-32">
                @foreach ($years as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <livewire:dashboard.modification-stats :page-filters="$filters" wire:key="dash-stats" />

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <livewire:dashboard.modifications-per-month-chart :page-filters="$filters" wire:key="dash-per-month" />
        </div>
        <livewire:dashboard.modification-status-chart :page-filters="$filters" wire:key="dash-status" />
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <livewire:dashboard.cost-by-year-chart :page-filters="$filters" wire:key="dash-cost" />
        <livewire:dashboard.sites-per-team-chart wire:key="dash-teams" />
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <livewire:dashboard.hgli-vip-sites-per-zone-chart wire:key="dash-hgli-vip" />
    </div>

</div>
