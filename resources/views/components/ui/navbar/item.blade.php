@props(['href' => null, 'current' => false, 'icon' => null, 'tooltip' => null])

<a @if ($href) href="{{ $href }}" @endif
    @if ($tooltip) title="{{ $tooltip }}" @endif
    {{ $attributes->class(['btn btn-ghost btn-sm gap-2', 'btn-active' => $current]) }}
    @if ($current) aria-current="page" @endif>
    @if ($icon)
        <x-ui.icon :name="$icon" class="size-4 shrink-0" />
    @endif
    {{ $slot }}
</a>
