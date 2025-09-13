<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        <flux:heading size="lg"> {{ $target }} PO</flux:heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <flux:field>
                            <flux:label>Subcontractor *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                {{-- <x-select-named-options wire:model="form.subcontractor_id" :options="$subcontractors"
                                    placeholder="Enter subcontractor" /> --}}

                                <flux:select wire:model="form.subcontractor_id" placeholder="Enter subcontractor"
                                    class="w-full">


                                    @foreach ($subcontractors as $subcontractor)
                                        <flux:select.option :value="$subcontractor->id">{{ $subcontractor->name }}
                                        </flux:select.option>
                                    @endforeach



                                </flux:select>
                            @else
                                <flux:input wire:model="form.subcontractor_id" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Subcontractor'" class="w-full" />
                            @endif

                            <flux:error name="form.subcontractor_id" />


                        </flux:field>



                        <flux:field>
                            <flux:label>PO Type *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-select-enums wire:model="form.type" :enumOptions="('App\Enums\ModificationPOs')::cases()" class="w-full"
                                    placeholder="Enter PO type" />
                            @else
                                <flux:input wire:model="form.type" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter PO type'" class="w-full" />
                            @endif

                            <flux:error name="form.type" />

                        </flux:field>
                        <flux:field>
                            <flux:label>PO number *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:input wire:model="form.po_number" class="w-full" type="number" :min=0
                                    placeholder="Enter PO number" />
                            @else
                                <flux:input wire:model="form.po_number" x-mask:dynamic="$money($input)"
                                    :placeholder="$readonly ? null : 'Enter PO number'" :readonly="$readonly"
                                    class="w-full" />
                            @endif
                            <flux:error name="form.po_number" />

                        </flux:field>

                        <flux:field>
                            <flux:label>Amount *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:input wire:model="form.amount" class="w-full" x-mask:dynamic="$money($input)"
                                    placeholder="Enter PO amount" />
                            @else
                                <flux:input wire:model="form.amount" x-mask:dynamic="$money($input)"
                                    :placeholder="$readonly ? null : 'Enter PO amount'" :readonly="$readonly"
                                    class="w-full" />
                            @endif
                            <flux:error name="form.amount" />

                        </flux:field>

                        <flux:field>
                            <flux:label>Status *</flux:label>
                            @if ($target == 'Update' || $target == 'New')
                                <flux:select wire:model="form.status" type="text" placeholder="Enter Status"
                                    class="w-full">

                                    <flux:select.option value="open">Open
                                    </flux:select.option>
                                    <flux:select.option value="closed">Closed
                                    </flux:select.option>

                                </flux:select>
                            @else
                                <flux:input wire:model="form.status" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter status'" class="w-full" />
                            @endif

                            <flux:error name="form.status" />

                        </flux:field>


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
