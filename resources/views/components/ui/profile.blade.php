@props(['name' => null, 'initials' => null, 'email' => null, 'chevron' => true])

<div tabindex="0" role="button"
    {{ $attributes->class('btn btn-ghost h-auto w-full justify-start gap-2 px-2 py-1.5 font-normal') }}>
    <span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-base-300 text-xs font-semibold">
        {{ $initials }}
    </span>

    @if ($name)
        <span class="grid flex-1 text-start leading-tight">
            <span class="truncate text-sm font-semibold">{{ $name }}</span>
            @if ($email)
                <span class="truncate text-xs opacity-60">{{ $email }}</span>
            @endif
        </span>
    @endif

    @if ($chevron)
        <x-ui.icon name="chevron-up-down" class="size-4 shrink-0 opacity-60" />
    @endif
</div>
