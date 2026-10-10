@props([
    'grid' => [],
    'current' => null,
    'importedAt' => null,
    'source' => null,
])

{{--
    The subtree as the planning sheet draws it: the site on the left, each
    level of cascade one column to the right, and every parent spanning the
    rows of everything beneath it. The planners' red / yellow / green fills
    are kept, because that colouring is most of how the sheet reads.
--}}

@php
    use App\Support\Sheets\SheetFill;

    $current = strtoupper(trim((string) $current));

    // shared with the routes card, so both drawings use the same fills
    $fillClass = fn (?string $fill) => SheetFill::classes($fill);
@endphp

<div {{ $attributes->class('w-full') }}>
    @if (! $grid || ! count($grid))
        <p class="py-4 text-sm text-base-content/60">
            {{ __('This site does not appear in any imported sheet.') }}
        </p>
    @else
        <div class="overflow-x-auto rounded-box border border-base-300">
            <table class="w-full border-collapse text-center text-xs">
                <caption class="sr-only">
                    {{ __('Cascade chain as drawn in the planning sheet') }}
                </caption>
                <tbody>
                    @foreach ($grid as $cells)
                        <tr>
                            @foreach ($cells as $cell)
                                @php $isCurrent = strtoupper($cell['code']) === $current; @endphp

                                <td rowspan="{{ $cell['rowspan'] }}"
                                    @class([
                                        'border border-base-300 px-3 py-2 align-middle font-semibold whitespace-normal',
                                        $fillClass($cell['fill']),
                                        'ring-2 ring-inset ring-primary' => $isCurrent,
                                    ])
                                    style="min-width: 11rem">
                                    <span class="font-mono">{{ $cell['code'] }}</span>
                                    @if (! empty($cell['name']))
                                        <span class="font-normal"> {{ $cell['name'] }}</span>
                                    @endif

                                    @if (! empty($cell['mmu_near']) || ! empty($cell['mmu_far']))
                                        <span class="mt-1 block font-mono text-[0.65rem] font-normal opacity-80">
                                            {{ $cell['mmu_near'] }}{{ $cell['mmu_far'] ? ' / ' . $cell['mmu_far'] : '' }}
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-base-content/70">
            @if ($importedAt)
                {{-- say plainly how current this is; the planners edit these daily --}}
                <span class="inline-flex items-center gap-1.5">
                    <span class="font-medium">{{ __('Sheet read') }}</span>
                    <span class="badge badge-ghost badge-sm">{{ $importedAt }}</span>
                    @if ($source)
                        <span class="opacity-60">{{ $source }}</span>
                    @endif
                </span>
            @endif

            <span class="font-medium">{{ __('Sheet colours:') }}</span>
            <span class="inline-flex items-center gap-1.5">
                <span class="inline-block size-3 rounded-sm border border-base-300 bg-red-600"></span> {{ __('red') }}
            </span>
            <span class="inline-flex items-center gap-1.5">
                <span class="inline-block size-3 rounded-sm border border-base-300 bg-yellow-300"></span> {{ __('yellow') }}
            </span>
            <span class="inline-flex items-center gap-1.5">
                <span class="inline-block size-3 rounded-sm border border-base-300 bg-[#92D050]"></span> {{ __('green') }}
            </span>
            <span class="inline-flex items-center gap-1.5">
                <span class="inline-block size-3 rounded-sm border border-base-300 bg-base-100"></span> {{ __('none') }}
            </span>
            <span class="opacity-70">{{ __('carried over from the sheet as-is') }}</span>
        </div>
    @endif
</div>
