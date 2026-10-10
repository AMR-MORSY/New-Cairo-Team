@props(['label' => null, 'value' => null, 'description' => null, 'icon' => null])

<label class="flex cursor-pointer items-start gap-2.5">
    <input type="radio" value="{{ $value }}" {{ $attributes->class('radio radio-sm radio-primary mt-0.5') }} />
    <span class="grid gap-0.5">
        <span class="flex items-center gap-1.5 text-sm text-base-content/90">
            @if ($icon)
                <x-ui.icon :name="$icon" class="size-4 shrink-0" />
            @endif
            {{ $label ?? $slot }}
        </span>
        @if ($description)
            <span class="text-xs text-base-content/60">{{ $description }}</span>
        @endif
    </span>
</label>
