<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        {{-- <x-ui.heading size="lg"> {{ $target }} Battery Record</x-ui.heading> --}}
                         <x-ui.heading size="lg"> {{ $site->site_code }} - {{$site->site_name}} </x-ui.heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <x-ui.field>
                            <x-ui.label>Brand *</x-ui.label>
                            <x-ui.input wire:model="form.batteries_brand" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter batteries brand'" class="w-full" />
                            <x-ui.error name="form.batteries_brand" />
                        </x-ui.field>



                        <x-ui.field>
                            <x-ui.label>Stock</x-ui.label>
                            <x-ui.input wire:model="form.stock" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Stock'" class="w-full" />
                            <x-ui.error name="form.stock" />
                        </x-ui.field>

                      
                            <x-ui.field>
                                <x-ui.label>Battery Volt</x-ui.label>
                                <x-ui.input wire:model="form.battery_volt" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Batteries volt'" class="w-full" />
                                <x-ui.error name="form.battery_volt" />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.label>Battery Amp Hr</x-ui.label>
                                <x-ui.input wire:model="form.battery_amp_hr" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Battery Amp Hr'" class="w-full" />
                                <x-ui.error name="form.battery_amp_hr" />
                            </x-ui.field>
                      

                        <x-ui.field>
                            <x-ui.label>Category *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.select wire:model="form.category" type="text" placeholder="category"
                                    class="w-full">

                                    @foreach ($categories as $category)
                                        <x-ui.select.option :value="$category">{{ $category }}
                                        </x-ui.select.option>
                                    @endforeach



                                </x-ui.select>
                            @else
                                <x-ui.input wire:model="form.category" :placeholder="$readonly ? null : 'category'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.category" />
                        </x-ui.field>



                        <x-ui.field>
                            <x-ui.label>No. Strings *</x-ui.label>
                            <x-ui.input wire:model="form.no_strings" type="number" :min="1" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter no. strings'" class="w-full" />
                            <x-ui.error name="form.no_strings" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Batteries status</x-ui.label>
                            <x-ui.input wire:model="form.batteries_status" :readonly="$readonly" type="text"
                                :placeholder="$readonly ? null : 'Enter batteries status'" class="w-full" />
                            <x-ui.error name="form.batteries_status" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Installation Date *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input type="date" wire:model="form.installation_date"
                                    placeholder="Enter CW date" class="w-full" />
                            @else
                                <x-ui.input wire:model="form.installation_date" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter installation Date'" class="w-full" />
                            @endif
                            <x-ui.error name="form.installation_date" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Theft case date</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input type="date" wire:model="form.theft_case"
                                    placeholder="Enter theft case date" class="w-full" />
                            @else
                                <x-ui.input wire:model="form.theft_case" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter theft case Date'" class="w-full" />
                            @endif
                            <x-ui.error name="form.theft_case" />
                        </x-ui.field>

                        <x-ui.field>
                            {{-- <x-ui.label>Description *</x-ui.label> --}}
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.textarea label="Comment" placeholder="Comments..." rows="4"
                                    wire:model="form.comment" class="w-full" />
                            @else
                                <x-ui.textarea label="Comment" wire:model="form.comment" rows="4"
                                    :placeholder="$readonly ? null : 'Comments...'" :readonly="$readonly"
                                    class="w-full" />
                            @endif

                            <x-ui.error name="form.comment" />
                        </x-ui.field>


 


                    </div>






                </div>
                @if ($target == 'Update')
                    <x-ui.button variant="primary" color="zinc" wire:click='updateBatteryRecord' class=" cursor-pointer">
                        Update</x-ui.button>
                @endif
                @if ($target == 'New')
                    <x-ui.button variant="primary" color="zinc" wire:click='newBatteryRecord' class=" cursor-pointer">
                        Create</x-ui.button>
                @endif

            </form>


        </div>
    </div>
</div>
