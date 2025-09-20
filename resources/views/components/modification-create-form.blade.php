<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        <flux:heading size="lg"> {{ $target }} Modification</flux:heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <flux:field>
                            <flux:label>Subcontractor *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-select-named-options wire:model="form.subcontractor_id" :options="$subcontractors"
                                    placeholder="Enter subcontractor" />
                            @else
                                <flux:input wire:model="form.subcontractor_id" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Subcontractor'" class="w-full" />
                            @endif


                        </flux:field>



                        <flux:field>
                            <flux:label>Project *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-select-named-options wire:model="form.project_id" :options="$projects"
                                    placeholder="Enter project" />
                            @else
                                <flux:input wire:model="form.project_id" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Site Name'" class="w-full" />
                            @endif

                        </flux:field>


                        <flux:field>
                            <flux:label>Requester *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-select-named-options wire:model="form.requester_id" :options="$requesters"
                                    placeholder="Enter requester" />
                            @else
                                <flux:input wire:model="form.requester_id" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Site Name'" class="w-full" />
                            @endif

                        </flux:field>

                        <flux:field>
                            <flux:label>Status *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-select-named-options wire:model="form.modification_status_id" :options="$modificationStatus"
                                    placeholder="Enter requester" />
                            @else
                                <flux:input wire:model="form.modification_status_id" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Site Name'" class="w-full" />
                            @endif

                        </flux:field>

                        <flux:field>
                            <flux:label>Request Date *</flux:label>

                            @if ($target == 'Update' || $target == 'New')
                                <flux:input type="date" wire:model="form.request_date"
                                    placeholder="Enter request date" class="w-full" />
                            @else
                                <flux:input wire:model="form.request_date" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Site Name'" class="w-full" />
                            @endif
                            <flux:error name="form.request_date" />
                        </flux:field>

                        <flux:field>
                            <flux:label>CW Date *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:input type="date" wire:model="form.cw_date" placeholder="Enter CW date"
                                    class="w-full" />
                            @else
                                <flux:input wire:model="form.cw_date" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter CW Date'" class="w-full" />
                            @endif
                            <flux:error name="form.cw_date" />
                        </flux:field>
                        <flux:field>
                            <flux:label>D6 Date *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:input type="date" wire:model="form.d6_date" placeholder="Enter D6 date"
                                    class="w-full" />
                            @else
                                <flux:input wire:model="form.d6_date" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter D6 Date'" class="w-full" />
                            @endif
                            <flux:error name="form.d6_date" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Est. Cost *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:input wire:model="form.est_cost" class="w-full" x-mask:dynamic="$money($input)"
                                    placeholder="Enter Estimated Cost" />
                            @else
                                <flux:input wire:model="form.est_cost" x-mask:dynamic="$money($input)"
                                    :placeholder="$readonly ? null : 'Enter Estimated Cost'" :readonly="$readonly"
                                    class="w-full" />
                            @endif

                            <flux:error name="form.est_cost" />
                        </flux:field>
                        @if ($target == 'Details')
                            <flux:field>
                                <flux:label>Final Cost *</flux:label>

                                <flux:input wire:model="form.final_cost" x-mask:dynamic="$money($input)"
                                    :placeholder="$readonly ? null : 'Enter final Cost'" :readonly="$readonly"
                                    class="w-full" />

                            </flux:field>
                        @endif

                        <flux:field>
                            <flux:label>Reported *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:select wire:model="form.reported" type="text" placeholder="Reported??"
                                    class="w-full">

                                    <flux:select.option value=0>No
                                    </flux:select.option>
                                    <flux:select.option value=1>Yes
                                    </flux:select.option>

                                </flux:select>
                            @else
                                <flux:input wire:model="form.reported" :placeholder="$readonly ? null : 'Reported??'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.reported" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Reporting Date *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:input type="date" wire:model="form.reported_at"
                                    placeholder="Enter Reporting Date" class="w-full" />
                            @else
                                <flux:input wire:model="form.reported_at" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter reporting Date'" class="w-full" />
                            @endif
                            <flux:error name="form.reported_at" />
                        </flux:field>

                        <flux:field>
                            {{-- <flux:label>Description *</flux:label> --}}
                            @if ($target == 'Update' || $target == 'Create')
                                <flux:textarea label="Description *" placeholder="Action descriptions..." rows="4"
                                    wire:model="description" class="w-full" />
                            @else
                                <flux:textarea label="Description *" wire:model="description" rows="4"
                                    :placeholder="$readonly ? null : 'Action descriptions...'" :readonly="$readonly"
                                    class="w-full" />
                            @endif

                            <flux:error name="form.description" />
                        </flux:field>

                        <flux:field>
                            {{-- <flux:label>Description *</flux:label> --}}
                            @if ($target == 'Update' || $target == 'New')
                                <flux:textarea label="Pending *" placeholder="Reasons..." rows="2"
                                    wire:model="pending" class="w-full" />
                            @else
                                <flux:textarea label="Pending *" wire:model="pending" rows="4"
                                    :placeholder="$readonly ? null : 'Reasons...'" :readonly="$readonly"
                                    class="w-full" />
                            @endif

                            <flux:error name="form.pending" />
                        </flux:field>

                        @if ($modification)
                            <flux:field>
                                <flux:label>Validation *</flux:label>
                                <flux:input wire:model="form.reservation_status" :readonly="$readonly"
                                    class="w-full" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Expires At *</flux:label>
                                <flux:input wire:model="form.expires_at" :readonly="$readonly" class="w-full" />
                            </flux:field>

                            @if ($modification->reservation->is_expired)
                                @if ($target == 'Update')
                                    <flux:field>
                                        <flux:label>Activate *</flux:label>
                                        <flux:select wire:model="form.activate" placeholder="Activate" class="w-full">

                                          
                                            <flux:select.option :value=1>Activate
                                            </flux:select.option>

                                        </flux:select>
                                    </flux:field>
                                @endif


                            @endif
                        @endif


                        <flux:field>
                            <flux:label>Actions *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-select wire:model="form.action_id" placeholder="Select Actions" multiselect
                                    :options="$actions" option-label="name" option-value="id" />
                            @else
                                <flux:input wire:model="form.action_id" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Site Name'" class="w-full" />
                            @endif

                        </flux:field>

                        @if ($target == 'Details')
                            <flux:field>
                                <flux:label>WO code *</flux:label>

                                <flux:input wire:model="form.wo_code" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter reporting Date'" class="w-full" />



                            </flux:field>
                            <flux:field>
                                <flux:label>Action Owner *</flux:label>

                                <flux:input wire:model="form.action_owner" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter reporting Date'" class="w-full" />



                            </flux:field>
                            <flux:field>
                                <flux:label>Zone *</flux:label>

                                <flux:input wire:model="form.zone_id" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter reporting Date'" class="w-full" />



                            </flux:field>
                            <flux:field>
                                <flux:label>Area *</flux:label>

                                <flux:input wire:model="form.area_id" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter reporting Date'" class="w-full" />



                            </flux:field>
                        @endif







                    </div>







                </div>
                @if ($target == 'Update')
                    <flux:button variant="primary" color="zinc" wire:click='update' class=" cursor-pointer">
                        Update</flux:button>
                @endif
                @if ($target == 'New')
                    <flux:button variant="primary" color="zinc" wire:click='create' class=" cursor-pointer">
                        Create</flux:button>
                @endif

            </form>


        </div>
    </div>
</div>
