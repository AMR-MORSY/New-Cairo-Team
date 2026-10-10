<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        <x-ui.heading size="lg"> {{ $target }} PO</x-ui.heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <x-ui.field>
                            <x-ui.label>Subcontractor *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                {{-- <x-select-named-options wire:model="form.subcontractor_id" :options="$subcontractors"
                                    placeholder="Enter subcontractor" /> --}}

                                <x-ui.select wire:model="form.subcontractor_id" placeholder="Enter subcontractor"
                                    class="w-full">


                                    @foreach ($subcontractors as $subcontractor)
                                        <x-ui.select.option :value="$subcontractor->id">{{ $subcontractor->name }}
                                        </x-ui.select.option>
                                    @endforeach



                                </x-ui.select>
                            @else
                                <x-ui.input wire:model="form.subcontractor_id" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Subcontractor'" class="w-full" />
                            @endif

                            <x-ui.error name="form.subcontractor_id" />


                        </x-ui.field>



                        <x-ui.field>
                            <x-ui.label>PO Type *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-select-enums wire:model="form.type" :enumOptions="('App\Enums\ModificationPOs')::cases()" class="w-full"
                                    placeholder="Enter PO type" />
                            @else
                                <x-ui.input wire:model="form.type" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter PO type'" class="w-full" />
                            @endif

                            <x-ui.error name="form.type" />

                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>PO number *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input wire:model="form.po_number" class="w-full" type="number" :min=0
                                    placeholder="Enter PO number" />
                            @else
                                <x-ui.input wire:model="form.po_number" x-mask:dynamic="$money($input)"
                                    :placeholder="$readonly ? null : 'Enter PO number'" :readonly="$readonly"
                                    class="w-full" />
                            @endif
                            <x-ui.error name="form.po_number" />

                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Amount *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.input wire:model="form.amount" class="w-full" x-mask:dynamic="$money($input)"
                                    placeholder="Enter PO amount" />
                            @else
                                <x-ui.input wire:model="form.amount" x-mask:dynamic="$money($input)"
                                    :placeholder="$readonly ? null : 'Enter PO amount'" :readonly="$readonly"
                                    class="w-full" />
                            @endif
                            <x-ui.error name="form.amount" />

                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Status *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')
                                <x-ui.select wire:model="form.status" type="text" placeholder="Enter Status"
                                    class="w-full">

                                    <x-ui.select.option value="open">Open
                                    </x-ui.select.option>
                                    <x-ui.select.option value="closed">Closed
                                    </x-ui.select.option>

                                </x-ui.select>
                            @else
                                <x-ui.input wire:model="form.status" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter status'" class="w-full" />
                            @endif

                            <x-ui.error name="form.status" />

                        </x-ui.field>


                    </div>



                </div>
                @if ($target == 'Update')
                    <x-ui.button variant="primary" color="zinc" wire:click='update' class=" cursor-pointer">
                        Update</x-ui.button>
                @endif
                @if ($target == 'New')
                    <x-ui.button variant="primary" color="zinc" wire:click='create' class=" cursor-pointer">
                        Create</x-ui.button>
                @endif

            </form>


        </div>
    </div>
</div>
