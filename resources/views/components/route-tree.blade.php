@props([
    'nodes' => [],      // SiteRouteMap::routeTree() shape: [{code, name, children}]
    'current' => null,  // the site the tree leads to, highlighted
])

{{--
    Routes merged on their shared parents. A run of single children reads
    inline, "A <> B <> C"; where routes part, each branch hangs off the shared
    parent on its own line, "├─ <>" / "└─ <>", and carries on the same way.
--}}

@foreach ($nodes as $node)
    @php
        $chain = [];
        $tip = $node;
        while (true) {
            $chain[] = $tip;
            if (count($tip['children']) !== 1) {
                break;
            }
            $tip = $tip['children'][0];
        }
        $branches = count($tip['children']) > 1 ? $tip['children'] : [];
    @endphp

    <div {{ $attributes->class('grid gap-1') }}>
        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
            @foreach ($chain as $i => $site)
                @if ($i > 0)
                    <span class="font-mono text-xs opacity-50">&lt;&gt;</span>
                @endif

                <a href="{{ route('site.show', ['site' => $site['code']]) }}" wire:navigate
                    @class([
                        'inline-flex items-baseline gap-1 rounded px-1.5 py-0.5 hover:bg-base-200',
                        'bg-primary/15 ring-1 ring-primary/40' => $site['code'] === $current,
                    ])>
                    <span class="font-mono text-sm font-medium">{{ $site['code'] }}</span>
                    @if ($site['name'])
                        <span class="text-xs opacity-60">{{ $site['name'] }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        @if ($branches)
            <ul class="ms-4 grid gap-1">
                @foreach ($branches as $b => $branch)
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 shrink-0 font-mono text-xs opacity-50">{{ $loop->last ? '└─' : '├─' }} &lt;&gt;</span>
                        <x-route-tree :nodes="[$branch]" :current="$current" />
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endforeach
