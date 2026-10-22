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
                   <x-ui.heading size="xl">Category</x-ui.heading>
                   <x-ui.subheading>Select searching category and corresponding option</x-ui.subheading>
                   <x-ui.separator class="md:hidden" />
                   <form class="p-10 space-y-8" wire:submit="searchByCategory">

                       <x-ui.field>
                           <x-ui.label>Category *</x-ui.label>
                           <x-ui.select wire:model.live="category"
                               placeholder="Select the category and the corresponding options will be showed ">
                               @foreach ($categories as $category)
                                   <x-ui.select.option :value="$category['id']">{{ $category['name'] }}
                                   </x-ui.select.option>
                               @endforeach
                           </x-ui.select>
                           {{-- <x-ui.error name="category" /> --}}
                       </x-ui.field>
                       <x-ui.field>
                           <x-ui.label>Option *</x-ui.label>
                           <x-ui.select wire:model="option" placeholder="Select the corresponding option">
                               @foreach ($options as $option)
                                   <x-ui.select.option :value="$option['id']">{{ $option['name'] }}
                                   </x-ui.select.option>
                               @endforeach
                           </x-ui.select>
                           {{-- <x-ui.error name="option" /> --}}

                       </x-ui.field>
                       <x-ui.button variant="primary" color="zinc" type="submit" class=" cursor-pointer">
                           Search</x-ui.button>

                   </form>


                   {{-- </x-viewLayouts.main-view-layout> --}}
               </section>
           </div>



           {{-- //////////////////////////Modification Date/////////////////////////////// --}}

           <input type="radio" name="my_tabs_2" class="tab" aria-label="Date" />
           <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">

               <section class="w-full">
                   {{-- <x-viewLayouts.main-view-layout :heading="__('Date')" :subheading="__('Choose between the available dates Request,CW, or D6. ')"> --}}

                   <x-ui.heading size="xl">Date</x-ui.heading>
                   <x-ui.subheading>Choose between the available dates Request,CW, or D6.</x-ui.subheading>
                   <x-ui.separator class="md:hidden" />
                   <form class="p-6 space-y-8" wire:submit="date">
                       <x-ui.field>
                           <x-ui.label>Dates *</x-ui.label>
                           <x-ui.select wire:model="selected_date" class="w-full" placeholder="select date">

                               @foreach ($dates as $date)
                                   <x-ui.select.option :value="$date['id']">{{ $date['name'] }}
                                   </x-ui.select.option>
                               @endforeach
                           </x-ui.select>
                           {{-- <x-ui.error name="selected_date" /> --}}
                       </x-ui.field>

                       <x-ui.field>
                           <x-ui.label>From *</x-ui.label>
                           <x-ui.input type="date" wire:model="date_from" placeholder="Enter From date"
                               class="w-full" />
                           {{-- <x-ui.error name="date_from" /> --}}
                       </x-ui.field>


                       <x-ui.field>
                           <x-ui.label>To</x-ui.label>
                           <x-ui.input type="date" wire:model="date_to" placeholder="Enter To date" class="w-full" />
                           {{-- <x-ui.error name="date_to" /> --}}
                       </x-ui.field>

                       <x-ui.button variant="primary" color="zinc" type="submit" class=" cursor-pointer">
                           Search</x-ui.button>


                   </form>



                   {{-- </x-viewLayouts.main-view-layout> --}}
               </section>
           </div>

           {{-- //////////////////////////Modification Work order/////////////////////////////// --}}
           <input type="radio" name="my_tabs_2" class="tab" aria-label="Work Order" />

           <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">
               <section class="w-full">


                   <x-ui.heading size="xl">Work order</x-ui.heading>
                   <x-ui.subheading>Enter the work order code and press search. </x-ui.subheading>
                   <x-ui.separator class="md:hidden" />

                   <div>
                       <form wire:submit="searchWO" method="POST">


                           <x-ui.input.group>
                               <x-ui.input placeholder="work order code..." wire:model='work_order' />



                               <x-ui.button type="submit">Search</x-ui.button>
                              
                           </x-ui.input.group>
                            {{-- <x-ui.error name="work_order" /> --}}



                       </form>
                   </div>

               </section>

           </div>

       </div>
       <div class="  w-full  p-4">
           <livewire:tables.modification.site-modifications-table :modifications="$modifications"  />
       </div>

   </x-viewLayouts.main-view-layout>
