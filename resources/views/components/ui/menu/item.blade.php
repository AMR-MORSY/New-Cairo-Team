@props(['href' => null, 'icon' => null, 'as' => null, 'variant' => null])

<li>
    @if ($as === 'button')
        <button {{ $attributes->class(['flex w-full items-center gap-2', 'text-error' => $variant === 'danger']) }}>
            @if ($icon) <x-ui.icon :name="$icon" class="size-4 shrink-0" /> @endif
            <span class="grow text-start">{{ $slot }}</span>
        </button>
    @else
        <a @if ($href) href="{{ $href }}" @endif
            {{ $attributes->class(['flex items-center gap-2', 'text-error' => $variant === 'danger']) }}>
            @if ($icon) <x-ui.icon :name="$icon" class="size-4 shrink-0" /> @endif
            <span class="grow">{{ $slot }}</span>
        </a>
    @endif
</li>
