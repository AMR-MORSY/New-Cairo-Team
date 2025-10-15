 <!-- Modal Dialog -->
 <div>


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
         <button wire:click="$dispatch('closeModal')"
             class="text-gray-400 hover:text-gray-600 text-4xl leading-none cursor-pointer">
             &times;
         </button>
     </div>

     <!-- Modal Body - Scrollable -->
   
     <div class="flex-1 overflow-y-auto overflow-x-hidden p-6 max-w-2xl">

      

         <livewire:tables.site.searched-sites-table :props="$props" :key="'search-' . now()" />

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
