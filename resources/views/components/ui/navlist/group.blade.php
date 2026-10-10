@props(['heading' => null, 'expandable' => false, 'expanded' => false, 'icon' => null])

@if ($expandable)
    {{-- daisyUI drives this with <details>, so it works without JavaScript --}}
    <li {{ $attributes->except('class') }}>
        <details @if ($expanded) open @endif>
            <summary class="font-medium">
                @if ($icon)
                    <x-ui.icon :name="$icon" class="size-4" />
                @endif
                {{ $heading }}
            </summary>
            <ul class="gap-0.5">
                {{ $slot }}
            </ul>
        </details>
    </li>
@else
    @if ($heading)
        <li class="menu-title px-2 pt-3 text-xs uppercase tracking-wide">{{ $heading }}</li>
    @endif
    {{ $slot }}
@endif
