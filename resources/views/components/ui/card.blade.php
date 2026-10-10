@props(['title' => null, 'bordered' => true])

<div {{ $attributes->class(['card bg-base-100', 'border border-base-300' => $bordered]) }}>
    <div class="card-body">
        @if ($title)
            <h2 class="card-title text-base">{{ $title }}</h2>
        @endif
        {{ $slot }}
    </div>
</div>
