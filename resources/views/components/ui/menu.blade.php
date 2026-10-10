{{-- Renders as the dropdown panel; the sibling trigger carries tabindex="0". --}}
<ul tabindex="0"
    {{ $attributes->class('dropdown-content menu z-[100] w-60 gap-0.5 rounded-box border border-base-300 bg-base-100 p-2 shadow-lg') }}>
    {{ $slot }}
</ul>
