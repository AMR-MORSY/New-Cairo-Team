@props(['name', 'show' => false, 'variant' => null, 'closable' => true])

{{-- Native <dialog>, which daisyUI styles directly -- no JS library needed. --}}
<dialog id="modal-{{ $name }}" {{ $attributes->class('modal') }} @if ($show) open @endif>
    <div class="modal-box">
        {{ $slot }}
    </div>

    @if ($closable)
        <form method="dialog" class="modal-backdrop">
            <button aria-label="{{ __('Close') }}">close</button>
        </form>
    @endif
</dialog>

@if ($show)
    <script>
        // reopen after a Livewire round trip that re-rendered with errors
        (() => {
            const d = document.getElementById('modal-{{ $name }}');
            if (d && !d.open) d.showModal();
        })();
    </script>
@endif
