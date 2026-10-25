<section class="w-full">
    <x-viewLayouts.main-view-layout :heading="__('Import New Batteries')" :subheading="__('Add many batteries at once from an Excel or CSV file.')">

        <div class="grid gap-6">

            {{-- step 1: the file --}}
            <x-ui.card>
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="grid gap-1">
                        <h2 class="card-title text-base">1. Choose a file</h2>
                        <p class="text-sm opacity-70">
                            .xlsx, .xls or .csv, up to 5&nbsp;MB and 2,000 rows. The first row must be the header.
                            Every row is checked before anything is saved.
                        </p>
                    </div>

                    <x-ui.button size="sm" variant="outline" icon="arrow-down-tray" wire:click="downloadTemplate"
                        loading="downloadTemplate">
                        Download template
                    </x-ui.button>
                </div>

                <div class="mt-2" x-data="{ uploading: false, progress: 0 }"
                    x-on:livewire-upload-start="uploading = true; progress = 0"
                    x-on:livewire-upload-finish="uploading = false"
                    x-on:livewire-upload-cancel="uploading = false"
                    x-on:livewire-upload-error="uploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">

                    <input type="file" wire:model="file" accept=".xlsx,.xls,.csv"
                        wire:key="file-{{ count($rows) }}"
                        @class(['file-input file-input-bordered w-full max-w-xl', 'file-input-error' => $errors->has('file')]) />

                    <x-ui.error name="file" />

                    <div x-show="uploading" x-cloak class="mt-3 max-w-xl">
                        <progress class="progress progress-primary w-full" max="100" x-bind:value="progress"></progress>
                        <p class="text-xs opacity-70">Uploading <span x-text="progress"></span>%</p>
                    </div>

                    <div wire:loading.flex wire:target="file" class="mt-3 items-center gap-2 text-sm opacity-70">
                        <span class="loading loading-spinner loading-sm"></span> Checking rows…
                    </div>
                </div>

                <details class="collapse collapse-arrow mt-2 border border-base-300 bg-base-200/40">
                    <summary class="collapse-title text-sm font-medium">Column guide</summary>
                    <div class="collapse-content overflow-x-auto text-sm">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Column</th><th>Required</th><th>Allowed values</th></tr>
                            </thead>
                            <tbody>
                                <tr><td class="font-mono">site_code</td><td>yes</td><td>Must already be in the sites table. A site may have several batteries.</td></tr>
                                <tr><td class="font-mono">batteries_brand</td><td>yes</td><td>Free text (max 50)</td></tr>
                                <tr><td class="font-mono">no_strings</td><td>yes</td><td>1–100</td></tr>
                                <tr><td class="font-mono">category</td><td>no</td><td>{{ implode(', ', \App\Livewire\Forms\BatteryForm::CATEGORIES) }} (blank = New)</td></tr>
                                <tr><td class="font-mono">installation_date</td><td>unless theft_case</td><td>A date: an Excel date cell, or text such as 2026-03-15</td></tr>
                                <tr><td class="font-mono">theft_case</td><td>no</td><td>The date of the theft, if there was one</td></tr>
                                <tr><td class="font-mono">battery_volt, battery_amp_hr, batteries_status, stock</td><td>no</td><td>Free text (max 50)</td></tr>
                                <tr><td class="font-mono">comment</td><td>no</td><td>Free text (max 250)</td></tr>
                            </tbody>
                        </table>
                        <p class="mt-2 text-xs opacity-70">
                            A row is "already recorded" when the site has a battery with the same brand, installation date,
                            strings and category - so importing the same file twice adds nothing.
                        </p>
                    </div>
                </details>
            </x-ui.card>

            {{-- step 2: what the check found --}}
            @if ($rows)
                @php
                    $counts = $this->counts;
                    $tabs = [
                        'all' => ['All rows', count($rows), ''],
                        'ready' => ['Ready', $counts['ready'], 'text-success'],
                        'invalid' => ['Invalid', $counts['invalid'], 'text-error'],
                        'no_site' => ['No such site', $counts['no_site'], 'text-error'],
                        'exists' => ['Already recorded', $counts['exists'], 'text-warning'],
                        'duplicate' => ['Duplicated in file', $counts['duplicate'], 'text-warning'],
                    ];
                    $badges = [
                        'ready' => ['success', 'Ready'],
                        'invalid' => ['danger', 'Invalid'],
                        'no_site' => ['danger', 'No site'],
                        'exists' => ['warning', 'Exists'],
                        'duplicate' => ['warning', 'Duplicate'],
                    ];
                @endphp

                <x-ui.card>
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="grid gap-1">
                            <h2 class="card-title text-base">2. Review</h2>
                            <p class="text-sm opacity-70">
                                Only rows marked <span class="font-medium text-success">Ready</span> are imported.
                                Fix the others in the file and choose it again, or import the ready ones now.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <x-ui.button size="sm" variant="ghost" icon="x-mark" wire:click="clear">
                                Start over
                            </x-ui.button>

                            <x-ui.button size="sm" variant="primary" icon="plus-circle" wire:click="import"
                                loading="import" :disabled="$counts['ready'] === 0"
                                wire:confirm="Import {{ $counts['ready'] }} new batteries?">
                                Import {{ $counts['ready'] }} {{ Str::plural('battery', $counts['ready']) }}
                            </x-ui.button>
                        </div>
                    </div>

                    <div role="tablist" class="tabs tabs-border mt-2 flex-wrap">
                        @foreach ($tabs as $key => [$label, $count, $tone])
                            <button type="button" role="tab" wire:click="$set('show', '{{ $key }}')"
                                @class(['tab gap-2', 'tab-active' => $show === $key])>
                                {{ $label }}
                                <span @class(['font-semibold', $tone])>{{ $count }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div class="max-h-[60vh] overflow-auto rounded-box border border-base-300">
                        <table class="table table-sm table-pin-rows">
                            <thead>
                                <tr>
                                    <th class="w-12">Line</th>
                                    <th>Status</th>
                                    <th>Site code</th>
                                    <th>Brand</th>
                                    <th>Strings</th>
                                    <th>Category</th>
                                    <th>Installed</th>
                                    <th>Volt / Ah</th>
                                    <th>Problems</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->visibleRows as $row)
                                    <tr wire:key="row-{{ $row['line'] }}" @class(['bg-error/5' => in_array($row['status'], ['invalid', 'no_site'])])>
                                        <td class="opacity-60">{{ $row['line'] }}</td>
                                        <td>
                                            <x-ui.badge size="sm" :color="$badges[$row['status']][0]">
                                                {{ $badges[$row['status']][1] }}
                                            </x-ui.badge>
                                        </td>
                                        <td class="font-mono">{{ $row['data']['site_code'] ?: '—' }}</td>
                                        <td>{{ $row['data']['batteries_brand'] ?: '—' }}</td>
                                        <td>{{ $row['data']['no_strings'] ?: '—' }}</td>
                                        <td>{{ $row['data']['category'] ?: '—' }}</td>
                                        <td>{{ $row['data']['installation_date'] ?: '—' }}</td>
                                        <td>{{ implode(' / ', array_filter([$row['data']['battery_volt'], $row['data']['battery_amp_hr']])) ?: '—' }}</td>
                                        <td class="text-xs">
                                            @if ($row['errors'])
                                                <ul @class(['list-inside', 'list-disc text-error' => in_array($row['status'], ['invalid', 'no_site']), 'opacity-70' => ! in_array($row['status'], ['invalid', 'no_site'])])>
                                                    @foreach ($row['errors'] as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-6 text-center opacity-60">No rows in this group.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-ui.card>
            @endif
        </div>

    </x-viewLayouts.main-view-layout>
</section>
