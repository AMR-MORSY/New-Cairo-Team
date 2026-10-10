  <div class=" bg-gray-50 py-8 px-4 w-full">
      <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
          <div class="bg-white w-full max-w-2xl mx-auto px-7 py-5 shadow-lg rounded-lg overflow-hidden">


              <x-ui.subheading class=" py-4">You can add quotation items from price list items
              </x-ui.subheading>
              <form wire:submit="searchPriceList" method="POST">


                  <x-ui.input.group>
                      <x-ui.input placeholder="Search price list..." wire:model='search' />

                      <x-ui.button type="submit">Search</x-ui.button>


                  </x-ui.input.group>
                  <x-ui.error name="search" />

                  <div class=" mt-2 px-2">
                      <x-ui.fieldset>
                          <x-ui.radio.group wire:model="search_option">
                              <x-ui.radio value="item" label="Item No."
                                  description="Search the price list by the item no.." />
                              <x-ui.radio value="description" label="Item description"
                                  description="Search the price list by item's description ." />
                              <x-ui.radio value="mail" label="Mail Items"
                                  description="Search the approved by mails' items by item's description." />


                          </x-ui.radio.group>
                      </x-ui.fieldset>
                  </div>






              </form>

              @if ($selectedItems)
              <div class="max-w-xl xl:max-w-3xl  2xl:max-w-6xl">
                 <livewire:tables.modification.quotation-selected-price-list-items-table :selectedItems="$selectedItems" />
              </div>
                 
              @endif


          </div>
      </div>
      {{-- <livewire:tables.modification.price-list-item-table/> --}}
  </div>
