<div x-data="{ show: @entangle('show') }" x-show="show" x-cloak @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-y-auto">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50" wire:click="$dispatch('closeModal')"></div>

    <!-- Modal Content -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full" @click.stop>
            <button wire:click="$dispatch('closeModal')"
                class="text-gray-400 hover:text-gray-600 text-4xl leading-none cursor-pointer">
                &times;
            </button>

            @if ($component)
                @livewire($component, $arguments, key($component . now()))
            @endif

        </div>
    </div>
</div>

{{-- 
<style>
    [x-cloak] {
        display: none !important;
    }
</style> --}}
