<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        {{-- <flux:heading size="lg"> {{ $target }} Battery Record</flux:heading> --}}
                         <flux:heading size="lg"> {{ $site->site_code }} - {{$site->site_name}} </flux:heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <flux:field>
                            <flux:label>Brand *</flux:label>
                            <flux:input wire:model="form.batteries_brand" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter batteries brand'" class="w-full" />
                            <flux:error name="form.batteries_brand" />
                        </flux:field>



                        <flux:field>
                            <flux:label>Stock *</flux:label>
                            <flux:input wire:model="form.stock" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Stock'" class="w-full" />
                            <flux:error name="form.stock" />
                        </flux:field>

                      
                            <flux:field>
                                <flux:label>Battery Volt *</flux:label>
                                <flux:input wire:model="form.battery_volt" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Batteries volt'" class="w-full" />
                                <flux:error name="form.battery_volt" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Battery Amp Hr *</flux:label>
                                <flux:input wire:model="form.battery_amp_hr" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Battery Amp Hr'" class="w-full" />
                                <flux:error name="form.battery_amp_hr" />
                            </flux:field>
                      

                        <flux:field>
                            <flux:label>Category *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:select wire:model="form.category" type="text" placeholder="category"
                                    class="w-full">

                                    @foreach ($categories as $category)
                                        <flux:select.option :value="$category">{{ $category }}
                                        </flux:select.option>
                                    @endforeach



                                </flux:select>
                            @else
                                <flux:input wire:model="form.category" :placeholder="$readonly ? null : 'category'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.category" />
                        </flux:field>



                        <flux:field>
                            <flux:label>No. Strings *</flux:label>
                            <flux:input wire:model="form.no_strings" type="number" :min="1" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter no. strings'" class="w-full" />
                            <flux:error name="form.no_strings" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Batteries status *</flux:label>
                            <flux:input wire:model="form.batteries_status" :readonly="$readonly" type="text"
                                :placeholder="$readonly ? null : 'Enter batteries status'" class="w-full" />
                            <flux:error name="form.batteries_status" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Installation Date *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:input type="date" wire:model="form.installation_date"
                                    placeholder="Enter CW date" class="w-full" />
                            @else
                                <flux:input wire:model="form.installation_date" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter installation Date'" class="w-full" />
                            @endif
                            <flux:error name="form.installation_date" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Theft case date *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:input type="date" wire:model="form.theft_case"
                                    placeholder="Enter theft case date" class="w-full" />
                            @else
                                <flux:input wire:model="form.theft_case" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter theft case Date'" class="w-full" />
                            @endif
                            <flux:error name="form.theft_case" />
                        </flux:field>

                        <flux:field>
                            {{-- <flux:label>Description *</flux:label> --}}
                            @if ($target == 'Update' || $target == 'New')
                                <flux:textarea label="Comment *" placeholder="Comments..." rows="4"
                                    wire:model="form.comment" class="w-full" />
                            @else
                                <flux:textarea label="Comment *" wire:model="form.comment" rows="4"
                                    :placeholder="$readonly ? null : 'Comments...'" :readonly="$readonly"
                                    class="w-full" />
                            @endif

                            <flux:error name="form.comment" />
                        </flux:field>


 


                    </div>






                </div>
                @if ($target == 'Update')
                    <flux:button variant="primary" color="zinc" wire:click='updateBatteryRecord' class=" cursor-pointer">
                        Update</flux:button>
                @endif
                @if ($target == 'New')
                    <flux:button variant="primary" color="zinc" wire:click='newBatteryRecord' class=" cursor-pointer">
                        Create</flux:button>
                @endif

            </form>


        </div>
    </div>
</div>
