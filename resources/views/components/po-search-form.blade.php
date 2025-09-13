<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        <flux:heading size="lg"> Search POs</flux:heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <flux:field>
                            <flux:label>Subcontractor *</flux:label>

                            <flux:select wire:model="form.subcontractor_id" placeholder="Enter subcontractor"
                                class="w-full">


                                @foreach ($subcontractors as $subcontractor)
                                    <flux:select.option :value="$subcontractor->id">{{ $subcontractor->name }}
                                    </flux:select.option>
                                @endforeach



                            </flux:select>


                            <flux:error name="form.subcontractor_id" />


                        </flux:field>



                        <flux:field>
                            <flux:label>PO Type *</flux:label>

                            <x-select-enums wire:model="form.type" :enumOptions="('App\Enums\ModificationPOs')::cases()" class="w-full"
                                placeholder="Enter PO type" />


                            <flux:error name="form.type" />

                        </flux:field>
                        <flux:field>
                            <flux:label>PO number *</flux:label>

                            <flux:input wire:model="form.po_number" class="w-full" type="number" :min=0
                                placeholder="Enter PO number" />

                            <flux:error name="form.po_number" />

                        </flux:field>


                        <flux:field>
                            <flux:label>Status *</flux:label>

                            <flux:select wire:model="form.status" type="text" placeholder="Enter Status"
                                class="w-full">

                                <flux:select.option value="open">Open
                                </flux:select.option>
                                <flux:select.option value="closed">Closed
                                </flux:select.option>

                            </flux:select>


                            <flux:error name="form.status" />

                        </flux:field>


                    </div>



                </div>

                <flux:button variant="primary" color="zinc" wire:click='searchPOs' class=" cursor-pointer">
                    Search</flux:button>


            </form>


        </div>
    </div>
</div>
