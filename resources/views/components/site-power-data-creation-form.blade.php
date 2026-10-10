<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        {{-- <x-ui.heading size="lg"> {{ $target }} Battery Record</x-ui.heading> --}}
                        <x-ui.heading size="lg"> {{ $site->site_code }} - {{ $site->site_name }} </x-ui.heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <x-ui.field>
                            <x-ui.label>Power Source *</x-ui.label>
                            <x-ui.input wire:model="form.power_source" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter power source'" class="w-full" />
                            <x-ui.error name="form.power_source" />
                        </x-ui.field>



                        <x-ui.field>
                            <x-ui.label>PM Type *</x-ui.label>
                            <x-ui.input wire:model="form.power_meter_type" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter power meter type'" class="w-full" />
                            <x-ui.error name="form.power_meter_type" />
                        </x-ui.field>


                        <x-ui.field>
                            <x-ui.label>Gen. Config. *</x-ui.label>
                            <x-ui.input wire:model="form.gen_config" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Gen. Config.'" class="w-full" />
                            <x-ui.error name="form.gen_config" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Gen. Serial *</x-ui.label>
                            <x-ui.input wire:model="form.gen_serial" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Gen. serial'" class="w-full" />
                            <x-ui.error name="form.gen_serial" />
                        </x-ui.field>


                        <x-ui.field>
                            <x-ui.label>Gen. Capacity *</x-ui.label>
                            <x-ui.input wire:model="form.gen_capacity" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Gen. capacity'" class="w-full" />
                            <x-ui.error name="form.gen_capacity" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Power Consumption *</x-ui.label>
                            <x-ui.input wire:model="form.overhaul_consumption" type="number"
                                :min="1" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter overhaul power consumption'" class="w-full" />
                            <x-ui.error name="form.overhaul_consumption" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Cable Length *</x-ui.label>
                            <x-ui.input wire:model="form.c_length" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter cable length'" class="w-full" />
                            <x-ui.error name="form.c_length" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Cable Size *</x-ui.label>
                            <x-ui.input wire:model="form.c_size" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter cable size'" class="w-full" />
                            <x-ui.error name="form.c_size" />
                        </x-ui.field>







                    </div>






                </div>
                @if ($target == 'Update')
                    <x-ui.button variant="primary" color="zinc" wire:click='updatePowerData'
                        class=" cursor-pointer">
                        Update</x-ui.button>
                @endif
                @if ($target == 'New')
                    <x-ui.button variant="primary" color="zinc" wire:click='newPowerDataRecord' class=" cursor-pointer">
                        Create</x-ui.button>
                @endif

            </form>


        </div>
    </div>
</div>
