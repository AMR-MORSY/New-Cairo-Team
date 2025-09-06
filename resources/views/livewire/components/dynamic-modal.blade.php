<div>
    <!-- Modal Backdrop -->
    @if ($isOpen)
        <div x-data x-show="true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed bg-gray-800/40 backdrop-blur-sm backdrop-saturate-150 inset-0 z-20 flex items-center justify-center p-4" style="display: none;" x-show="true">

            <!-- Modal Dialog -->
            <div x-show="true" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                @click.away="$wire.close()"
                class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">

                <!-- Modal Header -->
                <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{-- Title can be passed as a prop or set by the child component --}}
                        @if (isset($props['title']))
                            {{ $props['title'] }}
                        @else
                            Modal
                        @endif
                    </h3>
                    <button wire:click="close" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">
                        &times;
                    </button>
                </div>

                <!-- Modal Body - Scrollable -->
                <div class="flex-1 overflow-y-auto overflow-x-hidden p-6">
                    @if ($component)
                       {{-- <livewire:tables.site.searched-sites-table :$props /> --}}
                        @livewire($component,["props"=>$props]) 
                        {{-- <livewire:$component :props=$props :key="'dynamic-modal-' . $component . '-' . now()->timestamp"/> --}}
                    @endif
                </div>

                <!-- Optional Footer -->
                @if (isset($props['showFooter']) && $props['showFooter'])
                    <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-2">
                        <button wire:click="close" type="button"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Close
                        </button>
                        @if (isset($props['primaryButton']))
                            <button wire:click="{{ $props['primaryButtonAction'] ?? 'save' }}" type="button"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                {{ $props['primaryButton'] }}
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
