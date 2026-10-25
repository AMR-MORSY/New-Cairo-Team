<div class="grid gap-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <label class="input input-sm w-full max-w-xs">
            <x-icons.magnifying-glass class="size-4 opacity-50" />
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search code or name') }}" />
        </label>

        <div class="flex flex-wrap items-center gap-3">
            <span class="text-sm opacity-70">
                {{ trans_choice(':count site|:count sites', count($cards), ['count' => count($cards)]) }}
                · {{ __('red routes hidden') }}
            </span>

            <x-ui.button size="sm" variant="outline" icon="arrow-down-tray" wire:click="export" loading="export"
                :disabled="count($cards) === 0">
                {{ __('Export to Excel') }}
            </x-ui.button>
        </div>
    </div>

    @forelse ($cards as $card)
        <x-ui.card wire:key="routes-{{ $card['code'] }}">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('site.show', ['site' => $card['code']]) }}" wire:navigate
                    class="font-mono font-semibold hover:underline">{{ $card['code'] }}</a>
                <span class="font-medium">{{ $card['name'] }}</span>
                <x-ui.badge size="sm">{{ $card['category'] }}</x-ui.badge>

                @if (count($card['routes']) > 1)
                    <x-ui.badge size="sm" color="warning">{{ count($card['routes']) }} {{ __('routes') }}</x-ui.badge>
                @elseif (count($card['routes']) === 1)
                    <x-ui.badge size="sm">{{ __('1 route') }}</x-ui.badge>
                @endif
            </div>

            <div class="mt-2 rounded-box bg-base-200/50 p-3">
                @if ($card['tree'])
                    <x-route-tree :nodes="$card['tree']" :current="$card['code']" class="gap-2" />
                @else
                    <p class="text-sm opacity-70">{{ __('Not in any live route of the planning sheet.') }}</p>
                @endif
            </div>

            <div class="mt-2 overflow-x-auto rounded-box border border-base-300">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>{{ __('Site') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Brand') }}</th>
                            <th>{{ __('Strings') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Installed') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($card['routeSites'] as $site)
                            @forelse ($site['batteries'] as $battery)
                                <tr @class(['bg-primary/5' => $site['code'] === $card['code']])>
                                    @if ($loop->first)
                                        <td class="font-mono" rowspan="{{ count($site['batteries']) }}">{{ $site['code'] }}</td>
                                        <td rowspan="{{ count($site['batteries']) }}">{{ $site['name'] ?? '—' }}</td>
                                    @endif
                                    <td>{{ $battery['brand'] ?? '—' }}</td>
                                    <td>{{ $battery['strings'] ?? '—' }}</td>
                                    <td>{{ $battery['category'] ?? '—' }}</td>
                                    <td>{{ $battery['installed'] ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr @class(['bg-primary/5' => $site['code'] === $card['code']])>
                                    <td class="font-mono">{{ $site['code'] }}</td>
                                    <td>{{ $site['name'] ?? '—' }}</td>
                                    <td colspan="4" class="opacity-60">— {{ __('no batteries') }}</td>
                                </tr>
                            @endforelse
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    @empty
        <p class="py-6 text-center opacity-60">{{ __('No sites to show.') }}</p>
    @endforelse
</div>
