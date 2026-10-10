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
                            <x-ui.label>On Air Date *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input type="date" wire:model="form.on_air_date" placeholder="Enter on air Date"
                                    class="w-full" />
                            @else
                                <x-ui.input wire:model="form.on_air_date" :readonly="$readonly" class="w-full" />
                            @endif
                            <x-ui.error name="form.on_air_date" />
                        </x-ui.field>



                        <x-ui.field>
                            <x-ui.label>Topology *</x-ui.label>
                            <x-ui.input wire:model="form.topology" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Topology'" class="w-full" />
                            <x-ui.error name="form.topology" />
                        </x-ui.field>


                        <x-ui.field>
                            <x-ui.label>structure *</x-ui.label>
                            <x-ui.input wire:model="form.structure" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Structure'" class="w-full" />
                            <x-ui.error name="form.structure" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Equipment Room *</x-ui.label>
                            <x-ui.input wire:model="form.equip_room" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Equipment Room'" class="w-full" />
                            <x-ui.error name="form.equip_room" />
                        </x-ui.field>


                        <x-ui.field>
                            <x-ui.label>X.coordinate *</x-ui.label>
                            <x-ui.input wire:model="form.x_coordinate" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter x.coordinate'" class="w-full" />
                            <x-ui.error name="form.x_coordinate" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Y.coordinate *</x-ui.label>
                            <x-ui.input wire:model="form.y_coordinate" 
                                :readonly="$readonly" :placeholder="$readonly ? null : 'Enter y.coordinate'"
                                class="w-full" />
                            <x-ui.error name="form.y_coordinate" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>NTRA *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.select wire:model="form.ntra_cluster"  placeholder="Is NTRA?"
                                    class="w-full">

                                    <x-ui.select.option :value="0">No
                                    </x-ui.select.option>
                                    <x-ui.select.option :value="1">Yes
                                    </x-ui.select.option>


                                </x-ui.select>
                            @else
                                <x-ui.input wire:model="form.ntra_cluster" :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.ntra_cluster" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Serve Compound *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.select wire:model="form.serve_compound" type="text"
                                    placeholder="Serve Compound?" class="w-full">

                                    <x-ui.select.option :value="0">No
                                    </x-ui.select.option>
                                    <x-ui.select.option :value="1">Yes
                                    </x-ui.select.option>


                                </x-ui.select>
                            @else
                                <x-ui.input wire:model="form.serve_compound" :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.serve_compound" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Serve CEO *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.select wire:model="form.care_ceo" type="text" placeholder="Is Care of CEO"
                                    class="w-full">

                                    <x-ui.select.option :value="0">No
                                    </x-ui.select.option>
                                    <x-ui.select.option :value="1">Yes
                                    </x-ui.select.option>


                                </x-ui.select>
                            @else
                                <x-ui.input wire:model="form.care_ceo" :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.care_ceo" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Host Spot *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.select wire:model="form.hot_spot" type="text" placeholder="Is Hot spot?"
                                    class="w-full">

                                    <x-ui.select.option :value="0">No
                                    </x-ui.select.option>
                                    <x-ui.select.option :value="1">Yes
                                    </x-ui.select.option>


                                </x-ui.select>
                            @else
                                <x-ui.input wire:model="form.hot_spot" :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.hot_spot" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Universities *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.select wire:model="form.universities" type="text" placeholder="Is Hot spot?"
                                    class="w-full">

                                    <x-ui.select.option :value="0">No
                                    </x-ui.select.option>
                                    <x-ui.select.option :value="1">Yes
                                    </x-ui.select.option>


                                </x-ui.select>
                            @else
                                <x-ui.input wire:model="form.universities" :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.universities" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Axis *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.select wire:model="form.axis" type="text" placeholder="Is Axis?"
                                    class="w-full">

                                    <x-ui.select.option :value="0" >No
                                    </x-ui.select.option>
                                    <x-ui.select.option :value="1">Yes
                                    </x-ui.select.option>


                                </x-ui.select>
                            @else
                                <x-ui.input wire:model="form.axis" :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.axis" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Need Permission *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.select wire:model="form.need_access_permission" 
                                    placeholder="Need Permission?" class="w-full">

                                    <x-ui.select.option :value="0">No
                                    </x-ui.select.option>
                                    <x-ui.select.option :value="1">Yes
                                    </x-ui.select.option>


                                </x-ui.select>
                            @else
                                <x-ui.input wire:model="form.need_access_permission" :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.need_access_permission" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Permission Type *</x-ui.label>
                            <x-ui.input wire:model="form.permission_type" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Permission Type'" class="w-full" />
                            <x-ui.error name="form.permission_type" />
                        </x-ui.field>


                        <x-ui.field>
                            <x-ui.label>Last PM Date *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input type="date" wire:model="form.last_pm_date"
                                    placeholder="Enter on last PM Date" class="w-full" />
                            @else
                                <x-ui.input wire:model="form.last_pm_date" :readonly="$readonly" class="w-full" />
                            @endif
                            <x-ui.error name="form.last_pm_date" />
                        </x-ui.field>

                        <x-ui.field>
                        

                            <x-ui.textarea label="Address *" wire:model="form.address" rows="4"
                                :placeholder="$readonly ? null : 'Address...'" :readonly="$readonly"
                                class="w-full" />


                            <x-ui.error name="form.address" />
                        </x-ui.field>






                    </div>






                </div>
                @if ($target == 'Update')
                    <x-ui.button variant="primary" color="zinc" wire:click='updateSiteData' class=" cursor-pointer">
                        Update</x-ui.button>
                @endif
                @if ($target == 'New')
                    <x-ui.button variant="primary" color="zinc" wire:click='newSiteData'
                        class=" cursor-pointer">
                        Create</x-ui.button>
                @endif

            </form>


        </div>
    </div>
</div>
