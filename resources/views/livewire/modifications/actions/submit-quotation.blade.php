  <div class=" bg-gray-50 py-8 px-4 w-full">
      <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
          <div class="bg-white w-full max-w-2xl mx-auto px-7 py-5 shadow-lg rounded-lg overflow-hidden">


              <flux:subheading class=" py-4">You can add quotation items from price list items
              </flux:subheading>
              <form wire:submit="searchPriceList" method="POST">


                  <flux:input.group>
                      <flux:input placeholder="Search price list..." wire:model='search' />

                      <flux:button type="submit">Search</flux:button>


                  </flux:input.group>
                  <flux:error name="search" />

                  <div class=" mt-2 px-2">
                      <flux:fieldset>
                          <flux:radio.group wire:model="search_option">
                              <flux:radio value="item" label="Item No."
                                  description="Search the price list by the item no.." />
                              <flux:radio value="description" label="Item description"
                                  description="Search the price list by item's description ." />
                              <flux:radio value="mail" label="Mail Items"
                                  description="Search the approved by mails' items by item's description." />


                          </flux:radio.group>
                      </flux:fieldset>
                  </div>






              </form>

              @if ($selectedItems)
                  <livewire:tables.modification.quotation-selected-price-list-items-table :selectedItems="$selectedItems" />
              @endif


          </div>
      </div>
      {{-- <livewire:tables.modification.price-list-item-table/> --}}
  </div>
