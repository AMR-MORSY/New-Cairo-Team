@props(['href' => null, 'current' => false, 'icon' => null, 'badge' => null])

<li>
    <a @if ($href) href="{{ $href }}" @endif
        {{ $attributes->class(['flex items-center gap-2', 'menu-active font-medium' => $current]) }}
        @if ($current) aria-current="page" @endif>
        @if ($icon)
            <x-ui.icon :name="$icon" class="size-4 shrink-0" />
        @endif
        <span class="grow">{{ $slot }}</span>
        @if ($badge)
            <span class="badge badge-sm badge-ghost">{{ $badge }}</span>
        @endif
    </a>
</li>
