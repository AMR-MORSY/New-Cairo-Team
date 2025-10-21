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
                            <flux:label>On Air Date *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:input type="date" wire:model="form.on_air_date" placeholder="Enter on air Date"
                                    class="w-full" />
                            @else
                                <flux:input wire:model="form.on_air_date" :readonly="$readonly" class="w-full" />
                            @endif
                            <flux:error name="form.on_air_date" />
                        </flux:field>



                        <flux:field>
                            <flux:label>Topology *</flux:label>
                            <flux:input wire:model="form.topology" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Topology'" class="w-full" />
                            <flux:error name="form.topology" />
                        </flux:field>


                        <flux:field>
                            <flux:label>structure *</flux:label>
                            <flux:input wire:model="form.structure" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Structure'" class="w-full" />
                            <flux:error name="form.structure" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Equipment Room *</flux:label>
                            <flux:input wire:model="form.equip_room" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Equipment Room'" class="w-full" />
                            <flux:error name="form.equip_room" />
                        </flux:field>


                        <flux:field>
                            <flux:label>X.coordinate *</flux:label>
                            <flux:input wire:model="form.x_coordinate" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter x.coordinate'" class="w-full" />
                            <flux:error name="form.x_coordinate" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Y.coordinate *</flux:label>
                            <flux:input wire:model="form.y_coordinate" 
                                :readonly="$readonly" :placeholder="$readonly ? null : 'Enter y.coordinate'"
                                class="w-full" />
                            <flux:error name="form.y_coordinate" />
                        </flux:field>

                        <flux:field>
                            <flux:label>NTRA *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:select wire:model="form.ntra_cluster" type="text" placeholder="Is NTRA?"
                                    class="w-full">

                                    <flux:select.option :value="0">No
                                    </flux:select.option>
                                    <flux:select.option :value="1">Yes
                                    </flux:select.option>


                                </flux:select>
                            @else
                                <flux:input wire:model="form.ntra_cluster" :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.ntra_cluster" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Serve Compound *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:select wire:model="form.serve_compound" type="text"
                                    placeholder="Serve Compound?" class="w-full">

                                    <flux:select.option :value="0">No
                                    </flux:select.option>
                                    <flux:select.option :value="1">Yes
                                    </flux:select.option>


                                </flux:select>
                            @else
                                <flux:input wire:model="form.serve_compound" :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.serve_compound" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Serve CEO *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:select wire:model="form.care_ceo" type="text" placeholder="Is Care of CEO"
                                    class="w-full">

                                    <flux:select.option :value="0">No
                                    </flux:select.option>
                                    <flux:select.option :value="1">Yes
                                    </flux:select.option>


                                </flux:select>
                            @else
                                <flux:input wire:model="form.care_ceo" :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.care_ceo" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Host Spot *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:select wire:model="form.hot_spot" type="text" placeholder="Is Hot spot?"
                                    class="w-full">

                                    <flux:select.option :value="0">No
                                    </flux:select.option>
                                    <flux:select.option :value="1">Yes
                                    </flux:select.option>


                                </flux:select>
                            @else
                                <flux:input wire:model="form.hot_spot" :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.hot_spot" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Universities *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:select wire:model="form.universities" type="text" placeholder="Is Hot spot?"
                                    class="w-full">

                                    <flux:select.option :value="0">No
                                    </flux:select.option>
                                    <flux:select.option :value="1">Yes
                                    </flux:select.option>


                                </flux:select>
                            @else
                                <flux:input wire:model="form.universities" :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.universities" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Axis *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:select wire:model="form.axis" type="text" placeholder="Is Axis?"
                                    class="w-full">

                                    <flux:select.option :value="0" >No
                                    </flux:select.option>
                                    <flux:select.option :value="1">Yes
                                    </flux:select.option>


                                </flux:select>
                            @else
                                <flux:input wire:model="form.axis" :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.axis" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Need Permission *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:select wire:model="form.need_permission" type="text"
                                    placeholder="Need Permission?" class="w-full">

                                    <flux:select.option :value="0">No
                                    </flux:select.option>
                                    <flux:select.option :value="1">Yes
                                    </flux:select.option>


                                </flux:select>
                            @else
                                <flux:input wire:model="form.need_permission" :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.need_permission" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Permission Type *</flux:label>
                            <flux:input wire:model="form.permission_type" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Permission Type'" class="w-full" />
                            <flux:error name="form.permission_type" />
                        </flux:field>


                        <flux:field>
                            <flux:label>Last PM Date *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:input type="date" wire:model="form.last_pm_data"
                                    placeholder="Enter on last PM Date" class="w-full" />
                            @else
                                <flux:input wire:model="form.last_pm_data" :readonly="$readonly" class="w-full" />
                            @endif
                            <flux:error name="form.last_pm_data" />
                        </flux:field>

                        <flux:field>
                        

                            <flux:textarea label="Pending *" wire:model="address" rows="4"
                                :placeholder="$readonly ? null : 'Address...'" :readonly="$readonly"
                                class="w-full" />


                            <flux:error name="form.address" />
                        </flux:field>






                    </div>






                </div>
                @if ($target == 'Update')
                    <flux:button variant="primary" color="zinc" wire:click='updateSiteData' class=" cursor-pointer">
                        Update</flux:button>
                @endif
                @if ($target == 'New')
                    <flux:button variant="primary" color="zinc" wire:click='newSiteData'
                        class=" cursor-pointer">
                        Create</flux:button>
                @endif

            </form>


        </div>
    </div>
</div>
