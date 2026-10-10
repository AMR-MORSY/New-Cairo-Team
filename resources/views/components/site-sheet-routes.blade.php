@props([
    'grids' => [],
    'cascadeCount' => null,
    'current' => null,
    'isHub' => false,
])

{{--
    The sheet, redrawn: one column per level, every parent spanning the rows
    of everything beneath it. That merge is what says which site a cascade
    hangs off - indentation alone could not, which is why this replaced the
    nested list.

    One grid per route, not one merged grid: a site reached two ways is drawn
    twice in the sheet, and merging them buries which cascade hangs off which
    approach. The Sheet tab still shows the single combined picture.
--}}

@php
    use App\Support\Sheets\SheetFill;

    $current = strtoupper(trim((string) $current));

  
    // a group whose prefix is just this site is the sheet drawing it as a hub
    $routeCount = count(array_filter($grids, fn ($g) => count($g['route']) > 1));
@endphp

<div {{ $attributes->class('mt-5 rounded-box border border-base-300 bg-base-100') }}>
    <div class="flex flex-wrap items-center gap-2 border-b border-base-300 px-4 py-3">
        <x-icons.arrow-down-tray class="size-4 shrink-0 opacity-60" />
        <span class="font-semibold">{{ __('Routes from the planning sheet') }}</span>

        @if ($routeCount > 1)
            <span class="badge badge-warning badge-sm">
                {{ trans_choice(':count route|:count routes', $routeCount, ['count' => $routeCount]) }}
            </span>
        @elseif ($routeCount === 1)
            <span class="badge badge-ghost badge-sm">{{ __('1 route') }}</span>
        @endif

        @if ($isHub)
            <span class="badge badge-sm badge-primary">{{ __('hub') }}</span>
        @endif

        @if ($cascadeCount)
            <span class="badge badge-sm badge-neutral">
                {{ trans_choice(':count cascaded site|:count cascaded sites', $cascadeCount, ['count' => $cascadeCount]) }}
            </span>
        @endif

        @if ($routeCount > 1)
            <span class="text-xs text-base-content/60">
                {{ __('this site is reachable by more than one path') }}
            </span>
        @endif

        <span class="ms-auto flex flex-wrap items-center gap-2 text-xs text-base-content/60">
            <span>{{ __('Sheet colours:') }}</span>
            @foreach (SheetFill::FILLS as $fill)
                <span class="inline-flex items-center gap-1">
                    <span class="inline-block size-3 rounded-sm border border-base-300 {{ SheetFill::classes($fill) }}"></span>
                    {{ __(SheetFill::label($fill)) }}
                </span>
            @endforeach
        </span>
    </div>

    @if (! count($grids))
        <p class="px-4 py-4 text-sm text-base-content/60">
            @if ($isHub)
                {{ __('This site heads its own chain in the sheet; nothing feeds it.') }}
            @else
                {{ __('No route for this site in the imported sheets.') }}
            @endif
        </p>
    @else
        <div class="divide-y divide-base-300">
            @foreach ($grids as $g)
                @php
                    $hops = count($g['route']) - 1;
                    $n = $loop->iteration;
                @endphp

                <section class="px-4 py-3">
                    <div class="mb-2 flex flex-wrap items-center gap-2">
                        @if ($hops > 0)
                            <span class="badge badge-sm badge-neutral">{{ __('Route') }} {{ $n }}</span>
                            <span class="text-xs text-base-content/60">
                                {{ trans_choice(':count hop|:count hops', $hops, ['count' => $hops]) }}
                            </span>
                        @else
                            <span class="badge badge-sm badge-primary">{{ __('As hub') }}</span>
                            <span class="text-xs text-base-content/60">
                                {{ __('the sheet draws this site at the head of the chain') }}
                            </span>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="border-collapse">
                            <caption class="sr-only">
                                {{ __('Route') }} {{ $n }}: {{ implode(' > ', $g['route']) }}
                            </caption>
                            <tbody>
                                @foreach ($g['grid'] as $cells)
                                    <tr>
                                        @foreach ($cells as $cell)
                                            @php $isCurrent = strtoupper($cell['code']) === $current; @endphp

                                            {{-- align-middle is the tell: a parent sits level with the block it spans --}}
                                            <td rowspan="{{ $cell['rowspan'] }}"
                                                class="border border-base-200 px-1.5 py-1 align-middle">
                                                <span @class([
                                                    'inline-flex w-full items-center gap-1.5 whitespace-nowrap rounded-field border border-base-300 px-2 py-1 text-xs',
                                                    SheetFill::classes($cell['fill'] ?? null),
                                                    'font-semibold ring-2 ring-inset ring-primary' => $isCurrent,
                                                ])>
                                                    @if ($cell['depth'] === 0)
                                                        <span class="badge badge-xs badge-neutral">{{ __('hub') }}</span>
                                                    @endif

                                                    <span class="font-mono font-semibold">{{ $cell['code'] }}</span>

                                                    @if (! empty($cell['name']))
                                                        <span class="max-w-[8rem] truncate opacity-80 xl:max-w-[14rem]"
                                                            title="{{ $cell['name'] }}">{{ $cell['name'] }}</span>
                                                    @endif

                                                    @if (! empty($cell['mmu_near']) || ! empty($cell['mmu_far']))
                                                        <span class="shrink-0 font-mono text-[0.65rem] opacity-70"
                                                            title="{{ __('MMU ids for this hop') }}">
                                                            {{ $cell['mmu_near'] }}{{ $cell['mmu_far'] ? '/' . $cell['mmu_far'] : '' }}
                                                        </span>
                                                    @endif
                                                </span>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endforeach
        </div>
    @endif
</div>
