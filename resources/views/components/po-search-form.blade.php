<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        <x-ui.heading size="lg"> Search POs</x-ui.heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <x-ui.field>
                            <x-ui.label>Subcontractor *</x-ui.label>

                            <x-ui.select wire:model="form.subcontractor_id" placeholder="Enter subcontractor"
                                class="w-full">


                                @foreach ($subcontractors as $subcontractor)
                                    <x-ui.select.option :value="$subcontractor->id">{{ $subcontractor->name }}
                                    </x-ui.select.option>
                                @endforeach



                            </x-ui.select>


                            <x-ui.error name="form.subcontractor_id" />


                        </x-ui.field>



                        <x-ui.field>
                            <x-ui.label>PO Type *</x-ui.label>

                            <x-select-enums wire:model="form.type" :enumOptions="('App\Enums\ModificationPOs')::cases()" class="w-full"
                                placeholder="Enter PO type" />


                            <x-ui.error name="form.type" />

                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>PO number *</x-ui.label>

                            <x-ui.input wire:model="form.po_number" class="w-full" type="number" :min=0
                                placeholder="Enter PO number" />

                            <x-ui.error name="form.po_number" />

                        </x-ui.field>


                        <x-ui.field>
                            <x-ui.label>Status *</x-ui.label>

                            <x-ui.select wire:model="form.status" type="text" placeholder="Enter Status"
                                class="w-full">

                                <x-ui.select.option value="open">Open
                                </x-ui.select.option>
                                <x-ui.select.option value="closed">Closed
                                </x-ui.select.option>

                            </x-ui.select>


                            <x-ui.error name="form.status" />

                        </x-ui.field>


                    </div>



                </div>

                <x-ui.button variant="primary" color="zinc" wire:click='searchPOs' class=" cursor-pointer">
                    Search</x-ui.button>


            </form>


        </div>
    </div>
</div>
