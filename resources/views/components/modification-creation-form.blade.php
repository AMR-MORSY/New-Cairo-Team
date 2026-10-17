<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        <x-ui.heading size="lg"> {{ $target }} Modification</x-ui.heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <x-ui.field>
                            <x-ui.label>Subcontractor *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-select-named-options wire:model="form.subcontractor_id" :options="$subcontractors"
                                    placeholder="Enter subcontractor" />
                            @else
                                <x-ui.input wire:model="form.subcontractor_id" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter Subcontractor'"
                                    class="w-full" />
                            @endif


                        </x-ui.field>



                        <x-ui.field>
                            <x-ui.label>Project *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-select-named-options wire:model="form.project_id" :options="$projects"
                                    placeholder="Enter project" />
                            @else
                                <x-ui.input wire:model="form.project_id" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter Site Name'"
                                    class="w-full" />
                            @endif

                        </x-ui.field>


                        <x-ui.field>
                            <x-ui.label>Requester *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-select-named-options wire:model="form.requester_id" :options="$requesters"
                                    :selected="$modification?->requester_id" placeholder="Enter requester" />
                            @else
                                <x-ui.input wire:model="form.requester_id" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter Site Name'"
                                    class="w-full" />
                            @endif
                            {{-- <x-ui.error name="form.requester_id" /> --}}
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Status *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-select-named-options wire:model="form.status" :options="$modificationStatus" :selected="$modification?->status?->value"
                                    placeholder="Select status" />
                            @else
                                <x-ui.input wire:model="form.status" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter Site Name'"
                                    class="w-full" />
                            @endif
                            {{-- <x-ui.error name="form.status" /> --}}
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Request Date *</x-ui.label>

                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input type="date" wire:model="form.request_date"
                                    placeholder="Enter request date" class="w-full" />
                            @else
                                <x-ui.input wire:model="form.request_date" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter Site Name'"
                                    class="w-full" />
                            @endif
                            {{-- <x-ui.error name="form.request_date" /> --}}
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>CW Date *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input type="date" wire:model="form.cw_date" placeholder="Enter CW date"
                                    class="w-full" />
                            @else
                                <x-ui.input wire:model="form.cw_date" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter CW Date'"
                                    class="w-full" />
                            @endif
                            {{-- <x-ui.error name="form.cw_date" /> --}}
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>D6 Date *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input type="date" wire:model="form.d6_date" placeholder="Enter D6 date"
                                    class="w-full" />
                            @else
                                <x-ui.input wire:model="form.d6_date" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter D6 Date'"
                                    class="w-full" />
                            @endif
                            {{-- <x-ui.error name="form.d6_date" /> --}}
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Est. Cost *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input wire:model="form.est_cost" class="w-full" x-mask:dynamic="$money($input)"
                                    placeholder="Enter Estimated Cost" />
                            @else
                                <x-ui.input wire:model="form.est_cost" x-mask:dynamic="$money($input)"
                                    :placeholder="$readonly ? null : 'Enter Estimated Cost'" :readonly="$readonly" class="w-full" />
                            @endif

                            {{-- <x-ui.error name="form.est_cost" /> --}}
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Final Cost *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input wire:model="form.final_cost" class="w-full" x-mask:dynamic="$money($input)"
                                    placeholder="Enter Final Cost" />
                            @else
                                <x-ui.input wire:model="form.final_cost" x-mask:dynamic="$money($input)"
                                    :placeholder="$readonly ? null : 'Enter Final Cost'" :readonly="$readonly" class="w-full" />
                            @endif
                        </x-ui.field>


                        <x-ui.field>
                            <x-ui.label>Reported *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.select wire:model="form.reported" type="text" placeholder="Reported??"
                                    class="w-full">

                                    <x-ui.select.option value=0>No
                                    </x-ui.select.option>
                                    <x-ui.select.option value=1>Yes
                                    </x-ui.select.option>

                                </x-ui.select>
                            @else
                                <x-ui.input wire:model="form.reported" :placeholder="$readonly ? null : 'Reported??'" :readonly="$readonly"
                                    class="w-full" />
                            @endif

                            <x-ui.error name="form.reported" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Reporting Date *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input type="date" wire:model="form.reported_at"
                                    placeholder="Enter Reporting Date" class="w-full" />
                            @else
                                <x-ui.input wire:model="form.reported_at" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter reporting Date'"
                                    class="w-full" />
                            @endif
                            <x-ui.error name="form.reported_at" />
                        </x-ui.field>



                      
                        {{-- these fields concerned with new modifications system --}}

                        {{-- @if ($modification)
                            <x-ui.field>
                                <x-ui.label>Validation *</x-ui.label>
                                <x-ui.input wire:model="form.reservation_status" :readonly="$readonly"
                                    class="w-full" />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.label>Expires At *</x-ui.label>
                                <x-ui.input wire:model="form.expires_at" :readonly="$readonly" class="w-full" />
                            </x-ui.field>

                            @if ($modification->reservation->is_expired)
                                @if ($target == 'Update')
                                    <x-ui.field>
                                        <x-ui.label>Activate *</x-ui.label>
                                        <x-ui.select wire:model="form.activate" placeholder="Activate" class="w-full">

                                          
                                            <x-ui.select.option :value=1>Activate
                                            </x-ui.select.option>

                                        </x-ui.select>
                                    </x-ui.field>
                                @endif


                            @endif
                        @endif --}}




                        @if ($target == 'Details')
                            <x-ui.field>
                                <x-ui.label>WO code *</x-ui.label>

                                <x-ui.input wire:model="form.wo_code" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter reporting Date'"
                                    class="w-full" />



                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.label>Action Owner *</x-ui.label>

                                <x-ui.input wire:model="form.action_owner" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter reporting Date'"
                                    class="w-full" />



                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.label>Zone *</x-ui.label>

                                <x-ui.input wire:model="form.zone" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter reporting Date'"
                                    class="w-full" />



                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.label>Team *</x-ui.label>

                                <x-ui.input wire:model="form.team" :readonly="$readonly" :placeholder="$readonly ? null : 'Enter reporting Date'"
                                    class="w-full" />



                            </x-ui.field>
                        @endif







                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  gap-6">
                          <x-ui.field>

                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.textarea label="Description *" placeholder="Action descriptions..." rows="4"
                                    wire:model="form.description" class="w-full" />
                            @else
                                <x-ui.textarea label="Description *" wire:model="form.description" rows="4"
                                    :placeholder="$readonly ? null : 'Action descriptions...'" :readonly="$readonly" class="w-full" />
                            @endif

                            {{-- <x-ui.error name="form.description" /> --}}
                        </x-ui.field>

                        <x-ui.field>

                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.textarea label="Pending *" placeholder="Reasons..." rows="2"
                                    wire:model="form.pending" class="w-full" />
                            @else
                                <x-ui.textarea label="Pending *" wire:model="form.pending" rows="4" :placeholder="$readonly ? null : 'Reasons...'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            {{-- <x-ui.error name="form.pending" /> --}}
                        </x-ui.field>
                    </div>




                    <x-ui.field>
                        <x-ui.label>Actions *</x-ui.label>
                        @if ($target == 'Update' || $target == 'New')
                            {{-- through the wrapper, which flattens the models to
                                     id/name arrays; a bare x-select gets handed
                                     Eloquent objects and renders no options --}}
                            <x-select-named-options wire:model="form.action_id" placeholder="Select Actions" multiselect
                                :options="$actions" :selected="$modification?->actions->pluck('id')->all()" />
                        @else
                            {{-- a read-only view of a many-to-many: show the names, not the ids --}}
                            <x-ui.input :value="$modification?->actions->pluck('name')->implode(', ')" readonly class="w-full" />
                        @endif

                    </x-ui.field>


                </div>
                @if ($target == 'Update')
                    <x-ui.button variant="primary" color="zinc" wire:click='update' loading="update"
                        class=" cursor-pointer">Update</x-ui.button>
                @endif
                @if ($target == 'New')
                    <x-ui.button variant="primary" color="zinc" wire:click='create' loading="create"
                        class=" cursor-pointer">Create</x-ui.button>
                @endif

            </form>


        </div>
    </div>
</div>
