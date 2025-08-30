<div>
    @if ($quotation)
    @else
        <section class="w-full">
            <x-viewLayouts.main-view-layout :heading="__('Quotation Details')" :subheading="__('You will find very important information about the modification Quotation here. ')">


                {{-- <x-slot:links>
                    <livewire:modifications.modification-links :modification="$modification" />
                </x-slot:links> --}}

                <div class=" bg-gray-50 py-8 px-4 w-full">
                    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="bg-white w-full max-w-2xl mx-auto px-7 py-5 shadow-lg rounded-lg overflow-hidden">


                            <flux:subheading class=" py-4">You can add quotation items from price list items
                            </flux:subheading>
                            <form wire:submit="show" method="POST">


                                <flux:input.group>
                                    <flux:input placeholder="Search price list..." wire:model='search' />



                                    <flux:button type="submit">Search</flux:button>
                                </flux:input.group>

                                <div class=" mt-2 px-2">
                                    <flux:fieldset>
                                        <flux:radio.group wire:model="search_option">
                                            <flux:radio value="item" label="Item No." checked  description="Search by the item no. in the price list ."/>
                                            <flux:radio value="description" label="Item description" description="Search by the item description in the price list ." />

                                        </flux:radio.group>
                                    </flux:fieldset>
                                </div>




                            </form>
                           
                        </div>
                    </div>
                     <livewire:tables.modification.price-list-item-table/>
                </div>




            </x-viewLayouts.main-view-layout>
        </section>
    @endif
</div>
