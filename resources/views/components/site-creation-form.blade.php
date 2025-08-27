<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        <flux:heading size="lg"> Site {{ $target }}</flux:heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <flux:field>
                            <flux:label>Site Code *</flux:label>
                            <flux:input wire:model="form.site_code" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Site Code'" class="w-full" />
                            <flux:error name="form.site_code" />
                        </flux:field>



                        <flux:field>
                            <flux:label>Site Name *</flux:label>
                            <flux:input wire:model="form.site_name" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Site Name'" class="w-full" />
                            <flux:error name="form.site_name" />
                        </flux:field>

                        @if ($target == 'Information')
                            <flux:field>
                                <flux:label>Nodal Code *</flux:label>
                                <flux:input wire:model="form.nodal_code" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter nodal code'" class="w-full" />
                                <flux:error name="form.nodal_code" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Nodal Name *</flux:label>
                                <flux:input wire:model="form.nodal_name" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter nodal Name'" class="w-full" />
                                <flux:error name="form.nodal_name" />
                            </flux:field>
                        @endif



                        <flux:field>
                            <flux:label>BSC *</flux:label>
                            <flux:input wire:model="form.BSC" :readonly="$readonly" type="text"
                                :placeholder="$readonly ? null : 'Enter BSC'" class="w-full" />
                            <flux:error name="form.BSC" />
                        </flux:field>

                        <flux:field>
                            <flux:label>RNC *</flux:label>
                            <flux:input wire:model="form.RNC" :readonly="$readonly" type="text"
                                :placeholder="$readonly ? null : 'Enter RNC'" class="w-full" />
                            <flux:error name="form.RNC" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Office *</flux:label>
                            <flux:input wire:model="form.office" type="text" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Office'" class="w-full" />
                            <flux:error name="form.office" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Severity *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                {{-- <flux:select wire:model="form.severity" type="text" clearable
                                    :placeholder="$readonly ? null : 'Enter Site Severity'" class="w-full">
                                    @foreach (('App\Enums\SiteSeverities')::cases() as $severity)
                                        <flux:select.option :value="$severity->value">{{ $severity->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select> --}}
                                   <x-select-enums wire:model="form.severity" :enumOptions="('App\Enums\SiteSeverities')::cases()" class="w-full"
                                    placeholder="Enter Site Severity" />
                            @else
                                <flux:input wire:model="form.severity" type="text" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Office'" class="w-full" />

                            @endif

                            <flux:error name="form.severity" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Category *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                {{-- <flux:select wire:model="form.category" type="text" placeholder="Enter Site Category"
                                    class="w-full">
                                    @foreach (('App\Enums\SiteCategories')::cases() as $category)
                                        <flux:select.option :value="$category->value">{{ $category->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select> --}}
                                <x-select-enums wire:model="form.category" :enumOptions="('App\Enums\SiteCategories')::cases()" class="w-full"
                                    placeholder="Enter Site Category" />
                            @else
                                <flux:input wire:model="form.category" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Site Category'" class="w-full" />
                            @endif

                            <flux:error name="form.category" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Type *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                {{-- <flux:select wire:model="form.type" type="text" placeholder="Enter Site Type"
                                    class="w-full">
                                    @foreach (('App\Enums\SiteTypies')::cases() as $type)
                                        <flux:select.option :value="$type->value">{{ $type->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select> --}}
                                <x-select-enums wire:model="form.type" :enumOptions="('App\Enums\SiteTypies')::cases()" class="w-full"
                                    placeholder="Enter Site Type" />
                            @else
                                <flux:input wire:model="form.type" :placeholder="$readonly ? null : 'Enter Site Type'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.type" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Sharing *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                {{-- <flux:select wire:model="form.sharing" type="text" placeholder="Enter Sharing Status"
                                    class="w-full">
                                    @foreach (('App\Enums\SiteSharing')::cases() as $sharing)
                                        <flux:select.option :value="$sharing->value">{{ $sharing->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select> --}}
                                <x-select-enums wire:model="form.sharing" :enumOptions="('App\Enums\SiteSharing')::cases()" class="w-full"
                                    placeholder="Enter Sharing Status" />
                            @else
                                <flux:input wire:model="form.sharing"
                                    :placeholder="$readonly ? null : 'Enter Sharing Status'" :readonly="$readonly"
                                    class="w-full" />
                            @endif

                            <flux:error name="form.sharing" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Host *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                {{-- <flux:select wire:model="form.host" type="text" placeholder="Enter the Host"
                                    class="w-full">
                                    @foreach (('App\Enums\Host')::cases() as $host)
                                        <flux:select.option :value="$host->value">{{ $host->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select> --}}
                                <x-select-enums wire:model="form.host" :enumOptions="('App\Enums\Host')::cases()" class="w-full"
                                    placeholder="Enter Sharing Status" />
                            @else
                                <flux:input wire:model="form.host" :placeholder="$readonly ? null : 'Enter the host'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.host" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Guest *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                {{-- <flux:select wire:model="form.gest" type="text" placeholder="Enter the Guest"
                                    class="w-full">
                                    @foreach (('App\Enums\Guest')::cases() as $Guest)
                                        <flux:select.option :value="$Guest->value">{{ $Guest->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select> --}}
                                <x-select-enums wire:model="form.host" :enumOptions="('App\Enums\Guest')::cases()" class="w-full"
                                    placeholder="Enter the Guest" />
                            @else
                                <flux:input wire:model="form.gest" :placeholder="$readonly ? null : 'Enter the guest'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.gest" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Area *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                {{-- <flux:select wire:model="form.area" type="text" placeholder="Enter Area"
                                    class="w-full">
                                    @foreach (('App\Enums\Areas')::cases() as $OZ)
                                        <flux:select.option :value="$OZ->value">{{ $OZ->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select> --}}
                                <x-select-enums wire:model="form.area" :enumOptions="('App\Enums\Areas')::cases()" class="w-full"
                                    placeholder="Enter the Area" />
                            @else
                                <flux:input wire:model="form.area" :placeholder="$readonly ? null : 'Enter Area'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.area" />
                        </flux:field>
                        <flux:field>
                            <flux:label>VF Code *</flux:label>
                            <flux:input wire:model="form.vf_code" :placeholder="$readonly ? null : 'Enter VF Code'"
                                :readonly="$readonly" class="w-full" />
                            <flux:error name="form.vf_code" />
                        </flux:field>

                        <flux:field>
                            <flux:label>ET Code *</flux:label>
                            <flux:input wire:model="form.et_code" :placeholder="$readonly ? null : 'Enter ET Code'"
                                :readonly="$readonly" class="w-full" />
                            <flux:error name="form.et_code" />
                        </flux:field>

                        <flux:field>
                            <flux:label>We Code *</flux:label>
                            <flux:input wire:model="form.we_code" :placeholder="$readonly ? null : 'Enter WE Code'"
                                :readonly="$readonly" class="w-full" />
                            <flux:error name="form.we_code" />
                        </flux:field>

                        <flux:field>
                            <flux:label>2G Cells *</flux:label>
                            <flux:input wire:model="form.cells_2G" type="number" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter 2G Cells'" class="w-full" />
                            <flux:error name="form.cells_2G" />
                        </flux:field>
                        <flux:field>
                            <flux:label>3G Cells *</flux:label>
                            <flux:input wire:model="form.cells_3G" type="number" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter 3G Cells'" class="w-full" />
                            <flux:error name="form.cells_3G" />
                        </flux:field>
                        <flux:field>
                            <flux:label>4G Cells *</flux:label>
                            <flux:input wire:model="form.cells_4G" type="number" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter 4G Cells'" class="w-full" />
                            <flux:error name="form.cells_4G" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Status *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                {{-- <flux:select wire:model="form.status" type="text" placeholder="Enter Status"
                                    class="w-full">
                                    @foreach (('App\Enums\Status')::cases() as $status)
                                        <flux:select.option :value="$status->value" wire:key="{{ $status->value }}">
                                            {{ $status->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select> --}}

                                <x-select-enums wire:model="form.status" :enumOptions="('App\Enums\Status')::cases()" class="w-full"
                                    placeholder="Enter the Status" />
                            @else
                                <flux:input wire:model="form.status"
                                    :placeholder="$readonly ? null : 'Enter Site Status'" :readonly="$readonly"
                                    class="w-full" />
                            @endif

                            <flux:error name="form.status" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Zone *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                {{-- <flux:select wire:model="form.zone" type="text" placeholder="Enter Zone"
                                    class="w-full">
                                    @foreach (('App\Enums\Zones')::cases() as $zone)
                                        <flux:select.option :value="$zone->value">{{ $zone->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select> --}}
                                 <x-select-enums wire:model="form.zone" :enumOptions="('App\Enums\Zones')::cases()" class="w-full"
                                    placeholder="Enter the Zone" />
                                
                            @else
                                <flux:input wire:model="form.zone" :placeholder="$readonly ? null : 'Enter Site zone'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="form.zone" />
                        </flux:field>



                    </div>






                </div>
                @if ($target == 'Update')
                    <flux:button variant="primary" color="zinc" wire:click='updateSite' class=" cursor-pointer">
                        Update</flux:button>
                @endif
                @if ($target == 'Create')
                    <flux:button variant="primary" color="zinc" wire:click='create' class=" cursor-pointer">
                        Create</flux:button>
                @endif

            </form>


        </div>
    </div>
</div>
