   <x-viewLayouts.main-view-layout :heading="__('Modification Search')" :subheading="__('You can search modifications by category, dates and work order. ')">
       <div class="tabs tabs-border mb-6">
           {{-- /////////////////////////////////////////Modification Category//////////////////////////////////// --}}
           <input type="radio" name="my_tabs_2" class="tab" aria-label="Category" checked="checked" />


           <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">

               <section class="w-full">
                   {{-- <x-viewLayouts.main-view-layout :heading="__('Category')" :subheading="__('Select the category and see the available options. ')"> --}}
                   {{-- <x-slot:links>
                    <x-site-navlinks-group :site="$site" />
                </x-slot:links> --}}
                   <flux:heading size="xl">Category</flux:heading>
                   <flux:subheading>Select searching category and corresponding option</flux:subheading>
                   <flux:separator class="md:hidden" />
                   <form class="p-10 space-y-8" wire:submit="searchByCategory">

                       <flux:field>
                           <flux:label>Category *</flux:label>
                           <flux:select wire:model.live="category"
                               placeholder="Select the category and the corresponding options will be showed ">
                               @foreach ($categories as $category)
                                   <flux:select.option :value="$category['id']">{{ $category['name'] }}
                                   </flux:select.option>
                               @endforeach
                           </flux:select>
                           <flux:error name="category" />
                       </flux:field>
                       <flux:field>
                           <flux:label>Option *</flux:label>
                           <flux:select wire:model="option" placeholder="Select the corresponding option">
                               @foreach ($options as $option)
                                   <flux:select.option :value="$option['id']">{{ $option['name'] }}
                                   </flux:select.option>
                               @endforeach
                           </flux:select>
                           <flux:error name="option" />

                       </flux:field>
                       <flux:button variant="primary" color="zinc" type="submit" class=" cursor-pointer">
                           Search</flux:button>

                   </form>


                   {{-- </x-viewLayouts.main-view-layout> --}}
               </section>
           </div>



           {{-- //////////////////////////Modification Date/////////////////////////////// --}}

           <input type="radio" name="my_tabs_2" class="tab" aria-label="Date" />
           <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">

               <section class="w-full">
                   {{-- <x-viewLayouts.main-view-layout :heading="__('Date')" :subheading="__('Choose between the available dates Request,CW, or D6. ')"> --}}

                   <flux:heading size="xl">Date</flux:heading>
                   <flux:subheading>Choose between the available dates Request,CW, or D6.</flux:subheading>
                   <flux:separator class="md:hidden" />
                   <form class="p-6 space-y-8" wire:submit="date">
                       <flux:field>
                           <flux:label>Dates *</flux:label>
                           <flux:select wire:model="selected_date" class="w-full" placeholder="select date">

                               @foreach ($dates as $date)
                                   <flux:select.option :value="$date['id']">{{ $date['name'] }}
                                   </flux:select.option>
                               @endforeach
                           </flux:select>
                           <flux:error name="selected_date" />
                       </flux:field>

                       <flux:field>
                           <flux:label>From *</flux:label>
                           <flux:input type="date" wire:model="date_from" placeholder="Enter From date"
                               class="w-full" />
                           <flux:error name="date_from" />
                       </flux:field>


                       <flux:field>
                           <flux:label>To *</flux:label>
                           <flux:input type="date" wire:model="date_to" placeholder="Enter To date" class="w-full" />
                           <flux:error name="date_to" />
                       </flux:field>

                       <flux:button variant="primary" color="zinc" type="submit" class=" cursor-pointer">
                           Search</flux:button>


                   </form>



                   {{-- </x-viewLayouts.main-view-layout> --}}
               </section>
           </div>

           {{-- //////////////////////////Modification Work order/////////////////////////////// --}}
           <input type="radio" name="my_tabs_2" class="tab" aria-label="Work Order" />

           <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">
               <section class="w-full">


                   <flux:heading size="xl">Work order</flux:heading>
                   <flux:subheading>Enter the work order code and press search. </flux:subheading>
                   <flux:separator class="md:hidden" />

                   <div>
                       <form wire:submit="searchWO" method="POST">


                           <flux:input.group>
                               <flux:input placeholder="work order code..." wire:model='work_order' />



                               <flux:button type="submit">Search</flux:button>
                              
                           </flux:input.group>
                            <flux:error name="work_order" />



                       </form>
                   </div>

               </section>

           </div>

       </div>
       <div class="  w-full  p-4">
           <livewire:tables.modification.site-modifications-table :modifications="$modifications" :site="null" />
       </div>

   </x-viewLayouts.main-view-layout>
