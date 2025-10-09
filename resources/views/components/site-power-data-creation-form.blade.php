<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        {{-- <flux:heading size="lg"> {{ $target }} Battery Record</flux:heading> --}}
                        <flux:heading size="lg"> {{ $site->site_code }} - {{ $site->site_name }} </flux:heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <flux:field>
                            <flux:label>Power Source *</flux:label>
                            <flux:input wire:model="form.power_source" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter power source'" class="w-full" />
                            <flux:error name="form.power_source" />
                        </flux:field>



                        <flux:field>
                            <flux:label>PM Type *</flux:label>
                            <flux:input wire:model="form.power_meter_type" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter power meter type'" class="w-full" />
                            <flux:error name="form.power_meter_type" />
                        </flux:field>


                        <flux:field>
                            <flux:label>Gen. Config. *</flux:label>
                            <flux:input wire:model="form.gen_config" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Gen. Config.'" class="w-full" />
                            <flux:error name="form.gen_config" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Gen. Serial *</flux:label>
                            <flux:input wire:model="form.gen_serial" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Gen. serial'" class="w-full" />
                            <flux:error name="form.gen_serial" />
                        </flux:field>


                        <flux:field>
                            <flux:label>Gen. Capacity *</flux:label>
                            <flux:input wire:model="form.gen_capacity" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Gen. capacity'" class="w-full" />
                            <flux:error name="form.gen_capacity" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Power Consumption *</flux:label>
                            <flux:input wire:model="form.overhaul_consumption" type="number"
                                :min="1" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter overhaul power consumption'" class="w-full" />
                            <flux:error name="form.overhaul_consumption" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Cable Length *</flux:label>
                            <flux:input wire:model="form.c_length" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter cable length'" class="w-full" />
                            <flux:error name="form.c_length" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Cable Size *</flux:label>
                            <flux:input wire:model="form.c_size" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter cable size'" class="w-full" />
                            <flux:error name="form.c_size" />
                        </flux:field>







                    </div>






                </div>
                @if ($target == 'Update')
                    <flux:button variant="primary" color="zinc" wire:click='updatePowerData'
                        class=" cursor-pointer">
                        Update</flux:button>
                @endif
                @if ($target == 'New')
                    <flux:button variant="primary" color="zinc" wire:click='newPowerDataRecord' class=" cursor-pointer">
                        Create</flux:button>
                @endif

            </form>


        </div>
    </div>
</div>
