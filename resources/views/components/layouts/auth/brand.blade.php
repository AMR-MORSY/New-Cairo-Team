@props(["title" => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-svh bg-base-100 text-base-content antialiased">
    <div class="grid min-h-svh lg:grid-cols-2">

        {{-- Brand panel. Hidden on small screens, where a compact header stands in. --}}
        <aside class="relative hidden overflow-hidden bg-neutral text-neutral-content lg:flex lg:flex-col lg:justify-between lg:p-12">
            {{-- soft brand wash --}}
            <div aria-hidden="true"
                class="pointer-events-none absolute -end-24 -top-24 size-96 rounded-full bg-primary/25 blur-3xl"></div>
            <div aria-hidden="true"
                class="pointer-events-none absolute -bottom-32 -start-24 size-96 rounded-full bg-primary/10 blur-3xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <x-app-logo-icon class="size-9" />
                <span class="text-sm font-medium tracking-wide opacity-80">{{ config('app.name') }}</span>
            </div>

            <div class="relative z-10 max-w-md">
                <h1 class="text-6xl font-bold leading-[1.05] tracking-tight xl:text-7xl">
                    ALL<br />IN<br /><span class="text-primary">ONE</span>
                </h1>
                <p class="mt-6 text-base leading-relaxed opacity-70">
                    {{ __('Sites, modifications, pricing and reporting — one place, one workflow.') }}
                </p>
            </div>

            <p class="relative z-10 text-xs opacity-50">
                &copy; {{ date('Y') }} {{ config('app.name') }}
            </p>
        </aside>

        {{-- Sign-in panel --}}
        <main class="flex flex-col justify-center px-6 py-12 sm:px-12">
            <div class="mx-auto w-full max-w-sm">
                {{-- small-screen brand lockup --}}
                <div class="mb-10 flex items-center gap-3 lg:hidden">
                    <x-app-logo-icon class="size-9" />
                    <span class="text-2xl font-bold tracking-tight">
                        ALL IN <span class="text-primary">ONE</span>
                    </span>
                </div>

                {{ $slot }}
            </div>
        </main>
    </div>

</body>

</html>
