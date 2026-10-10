<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        <x-ui.heading size="lg"> Site {{ $target }}</x-ui.heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <x-ui.field>
                            <x-ui.label>Site Code *</x-ui.label>
                            <x-ui.input wire:model="form.site_code" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Site Code'" class="w-full" />
                            <x-ui.error name="form.site_code" />
                        </x-ui.field>



                        <x-ui.field>
                            <x-ui.label>Site Name *</x-ui.label>
                            <x-ui.input wire:model="form.site_name" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Site Name'" class="w-full" />
                            <x-ui.error name="form.site_name" />
                        </x-ui.field>

                        @if ($target == 'Information')
                            <x-ui.field>
                                <x-ui.label>Nodal Code *</x-ui.label>
                                <x-ui.input wire:model="form.nodal_code" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter nodal code'" class="w-full" />
                                <x-ui.error name="form.nodal_code" />
                            </x-ui.field>
                            <x-ui.field>
                                <x-ui.label>Nodal Name *</x-ui.label>
                                <x-ui.input wire:model="form.nodal_name" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter nodal Name'" class="w-full" />
                                <x-ui.error name="form.nodal_name" />
                            </x-ui.field>
                        @endif



                        <x-ui.field>
                            <x-ui.label>BSC *</x-ui.label>
                            <x-ui.input wire:model="form.BSC" :readonly="$readonly" type="text"
                                :placeholder="$readonly ? null : 'Enter BSC'" class="w-full" />
                            <x-ui.error name="form.BSC" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>RNC *</x-ui.label>
                            <x-ui.input wire:model="form.RNC" :readonly="$readonly" type="text"
                                :placeholder="$readonly ? null : 'Enter RNC'" class="w-full" />
                            <x-ui.error name="form.RNC" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Office *</x-ui.label>
                            <x-ui.input wire:model="form.office" type="text" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Office'" class="w-full" />
                            <x-ui.error name="form.office" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Severity *</x-ui.label>
                            @if ($target == 'Update' || $target == 'Create')
                                
                                   <x-select-enums wire:model="form.severity" :enumOptions="('App\Enums\SiteSeverities')::cases()" class="w-full"
                                    placeholder="Enter Site Severity" />
                            @else
                                <x-ui.input wire:model="form.severity" type="text" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Office'" class="w-full" />

                            @endif

                            <x-ui.error name="form.severity" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Category *</x-ui.label>
                            @if ($target == 'Update' || $target == 'Create')
                              
                                <x-select-enums wire:model="form.category" :enumOptions="('App\Enums\SiteCategories')::cases()" class="w-full"
                                    placeholder="Enter Site Category" />
                            @else
                                <x-ui.input wire:model="form.category" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Site Category'" class="w-full" />
                            @endif

                            <x-ui.error name="form.category" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Type *</x-ui.label>
                            @if ($target == 'Update' || $target == 'Create')
                               
                                <x-select-enums wire:model="form.type" :enumOptions="('App\Enums\SiteTypies')::cases()" class="w-full"
                                    placeholder="Enter Site Type" />
                            @else
                                <x-ui.input wire:model="form.type" :placeholder="$readonly ? null : 'Enter Site Type'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.type" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>Sharing *</x-ui.label>
                            @if ($target == 'Update' || $target == 'Create')
                            
                                <x-select-enums wire:model="form.sharing" :enumOptions="('App\Enums\SiteSharing')::cases()" class="w-full"
                                    placeholder="Enter Sharing Status" />
                            @else
                                <x-ui.input wire:model="form.sharing"
                                    :placeholder="$readonly ? null : 'Enter Sharing Status'" :readonly="$readonly"
                                    class="w-full" />
                            @endif

                            <x-ui.error name="form.sharing" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Host *</x-ui.label>
                            @if ($target == 'Update' || $target == 'Create')
                              
                                <x-select-enums wire:model="form.host" :enumOptions="('App\Enums\Host')::cases()" class="w-full"
                                    placeholder="Enter host" />
                            @else
                                <x-ui.input wire:model="form.host" :placeholder="$readonly ? null : 'Enter host'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.host" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Guest *</x-ui.label>
                            @if ($target == 'Update' || $target == 'Create')
                              
                                <x-select-enums wire:model="form.gest" :enumOptions="('App\Enums\Guest')::cases()" class="w-full"
                                    placeholder="Enter the Guest" />
                            @else
                                <x-ui.input wire:model="form.gest" :placeholder="$readonly ? null : 'Enter the guest'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.gest" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Team *</x-ui.label>
                            @if ($target == 'Update' || $target == 'Create')
                                <x-ui.select wire:model="form.team" type="text" placeholder="Enter Team"
                                    class="w-full">
                                    @foreach ($teams as $team)
                                        <x-ui.select.option :value="$team->value">{{ $team->label() }}
                                        </x-ui.select.option>
                                    @endforeach

                                </x-ui.select>
                              
                            @else
                                <x-ui.input wire:model="form.team" :placeholder="$readonly ? null : 'Enter Team'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.team" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>VF Code *</x-ui.label>
                            <x-ui.input wire:model="form.vf_code" :placeholder="$readonly ? null : 'Enter VF Code'"
                                :readonly="$readonly" class="w-full" />
                            <x-ui.error name="form.vf_code" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>ET Code *</x-ui.label>
                            <x-ui.input wire:model="form.et_code" :placeholder="$readonly ? null : 'Enter ET Code'"
                                :readonly="$readonly" class="w-full" />
                            <x-ui.error name="form.et_code" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>We Code *</x-ui.label>
                            <x-ui.input wire:model="form.we_code" :placeholder="$readonly ? null : 'Enter WE Code'"
                                :readonly="$readonly" class="w-full" />
                            <x-ui.error name="form.we_code" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>2G Cells *</x-ui.label>
                            <x-ui.input wire:model="form.cells_2G" type="number" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter 2G Cells'" class="w-full" />
                            <x-ui.error name="form.cells_2G" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>3G Cells *</x-ui.label>
                            <x-ui.input wire:model="form.cells_3G" type="number" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter 3G Cells'" class="w-full" />
                            <x-ui.error name="form.cells_3G" />
                        </x-ui.field>
                        <x-ui.field>
                            <x-ui.label>4G Cells *</x-ui.label>
                            <x-ui.input wire:model="form.cells_4G" type="number" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter 4G Cells'" class="w-full" />
                            <x-ui.error name="form.cells_4G" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Status *</x-ui.label>
                            @if ($target == 'Update' || $target == 'Create')
                              

                                <x-select-enums wire:model="form.status" :enumOptions="('App\Enums\Status')::cases()" class="w-full"
                                    placeholder="Enter the Status" />
                            @else
                                <x-ui.input wire:model="form.status"
                                    :placeholder="$readonly ? null : 'Enter Site Status'" :readonly="$readonly"
                                    class="w-full" />
                            @endif

                            <x-ui.error name="form.status" />
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Zone *</x-ui.label>
                            @if ($target == 'Update' || $target == 'Create')
                                <x-ui.select wire:model="form.zone" type="text" placeholder="Enter Zone"
                                    class="w-full">
                                    @foreach ($zones as $zone)
                                        <x-ui.select.option :value="$zone->value">{{ $zone->label() }}
                                        </x-ui.select.option>
                                    @endforeach

                                </x-ui.select>
                              
                                
                            @else
                                <x-ui.input wire:model="form.zone" :placeholder="$readonly ? null : 'Enter Site zone'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <x-ui.error name="form.zone" />
                        </x-ui.field>



                    </div>






                </div>
                @if ($target == 'Update')
                    <x-ui.button variant="primary" color="zinc" wire:click='update' class=" cursor-pointer">
                        Update</x-ui.button>
                @endif
                @if ($target == 'Create')
                    <x-ui.button variant="primary" color="zinc" wire:click='create' class=" cursor-pointer">
                        Create</x-ui.button>
                @endif

            </form>


        </div>
    </div>
</div>
