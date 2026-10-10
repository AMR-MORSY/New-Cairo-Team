<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

{{-- <link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png"> --}}


<link rel="icon" href="/orange-app-logo2.svg" sizes="any">
<link rel="icon" href="/orange-app-logo2.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/orange-app-logo2.svg">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

 
{{-- Appearance bootstrap. Runs before paint so the theme never flashes.
     Keeps two things in sync: the `dark` class (Tailwind / remaining Flux
     styles) and `data-theme` (daisyUI). --}}
<script>
    (() => {
        const THEMES = { light: 'cairo', dark: 'cairo-dark' };

        const stored = () => {
            try {
                // 'flux.appearance' is read once so an existing preference carries over
                return localStorage.getItem('appearance')
                    || localStorage.getItem('flux.appearance')
                    || 'system';
            } catch { return 'system'; }
        };

        const resolve = (choice) => choice === 'system'
            ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
            : choice;

        const paint = (choice) => {
            const mode = resolve(choice);
            const root = document.documentElement;
            root.classList.toggle('dark', mode === 'dark');
            root.dataset.theme = THEMES[mode];
            root.style.colorScheme = mode;
        };

        window.appearance = {
            get current() { return stored(); },
            set(choice) {
                try { localStorage.setItem('appearance', choice); } catch {}
                paint(choice);
                window.dispatchEvent(new CustomEvent('appearance-changed', { detail: choice }));
            },
        };

        paint(stored());

        window.matchMedia('(prefers-color-scheme: dark)')
            .addEventListener('change', () => { if (stored() === 'system') paint('system'); });
    })();
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

