<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form  class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        <flux:heading size="lg"> Site {{ $target }}</flux:heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <flux:field>
                            <flux:label>Site Code *</flux:label>
                            <flux:input wire:model="site_code" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Site Code'" class="w-full" />
                            <flux:error name="site_code" />
                        </flux:field>



                        <flux:field>
                            <flux:label>Site Name *</flux:label>
                            <flux:input wire:model="site_name" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Site Name'" class="w-full" />
                            <flux:error name="site_name" />
                        </flux:field>

                        <flux:field>
                            <flux:label>BSC *</flux:label>
                            <flux:input wire:model="BSC" :readonly="$readonly" type="text"
                                :placeholder="$readonly ? null : 'Enter BSC'" class="w-full" />
                            <flux:error name="BSC" />
                        </flux:field>

                        <flux:field>
                            <flux:label>RNC *</flux:label>
                            <flux:input wire:model="RNC" :readonly="$readonly" type="text"
                                :placeholder="$readonly ? null : 'Enter RNC'" class="w-full" />
                            <flux:error name="RNC" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Office *</flux:label>
                            <flux:input wire:model="office" type="text" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter Office'" class="w-full" />
                            <flux:error name="office" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Severity *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                <flux:select wire:model="severity" type="text"
                                    :placeholder="$readonly ? null : 'Enter Site Severity'" class="w-full">
                                    @foreach (('App\Enums\SiteSeverities')::cases() as $severity)
                                        <flux:select.option :value="$severity->value">{{ $severity->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select>
                            @else
                                <flux:input wire:model="severity" type="text" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Office'" class="w-full" />

                            @endif

                            <flux:error name="severity" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Category *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                <flux:select wire:model="category" type="text" placeholder="Enter Site Category"
                                    class="w-full">
                                    @foreach (('App\Enums\SiteCategories')::cases() as $category)
                                        <flux:select.option :value="$category->value">{{ $category->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select>
                            @else
                                <flux:input wire:model="category" :readonly="$readonly"
                                    :placeholder="$readonly ? null : 'Enter Site Category'" class="w-full" />
                            @endif

                            <flux:error name="category" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Type *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                <flux:select wire:model="type" type="text" placeholder="Enter Site Type"
                                    class="w-full">
                                    @foreach (('App\Enums\SiteTypies')::cases() as $type)
                                        <flux:select.option :value="$type->value">{{ $type->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select>
                            @else
                                <flux:input wire:model="type" :placeholder="$readonly ? null : 'Enter Site Type'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="type" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Sharing *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                <flux:select wire:model="sharing" type="text" placeholder="Enter Sharing Status"
                                    class="w-full">
                                    @foreach (('App\Enums\SiteSharing')::cases() as $sharing)
                                        <flux:select.option :value="$sharing->value">{{ $sharing->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select>
                            @else
                                <flux:input wire:model="sharing"
                                    :placeholder="$readonly ? null : 'Enter Sharing Status'" :readonly="$readonly"
                                    class="w-full" />
                            @endif

                            <flux:error name="sharing" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Host *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                <flux:select wire:model="host" type="text" placeholder="Enter the Host"
                                    class="w-full">
                                    @foreach (('App\Enums\Host')::cases() as $host)
                                        <flux:select.option :value="$host->value">{{ $host->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select>
                            @else
                                <flux:input wire:model="host" :placeholder="$readonly ? null : 'Enter the host'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="host" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Guest *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                <flux:select wire:model="gest" type="text" placeholder="Enter the Guest"
                                    class="w-full">
                                    @foreach (('App\Enums\Guest')::cases() as $Guest)
                                        <flux:select.option :value="$Guest->value">{{ $Guest->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select>
                            @else
                                <flux:input wire:model="gest" :placeholder="$readonly ? null : 'Enter the guest'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="guest" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Oz *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                <flux:select wire:model="oz" type="text" placeholder="Enter OZ" class="w-full">
                                    @foreach (('App\Enums\OZ')::cases() as $OZ)
                                        <flux:select.option :value="$OZ->value">{{ $OZ->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select>
                            @else
                                <flux:input wire:model="oz" :placeholder="$readonly ? null : 'Enter Site OZ'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="oz" />
                        </flux:field>
                        <flux:field>
                            <flux:label>VF Code *</flux:label>
                            <flux:input wire:model="vf_code" :placeholder="$readonly ? null : 'Enter VF Code'"
                                :readonly="$readonly" class="w-full" />
                            <flux:error name="vf_code" />
                        </flux:field>

                        <flux:field>
                            <flux:label>ET Code *</flux:label>
                            <flux:input wire:model="et_code" :placeholder="$readonly ? null : 'Enter ET Code'"
                                :readonly="$readonly" class="w-full" />
                            <flux:error name="et_code" />
                        </flux:field>

                        <flux:field>
                            <flux:label>We Code *</flux:label>
                            <flux:input wire:model="we_code" :placeholder="$readonly ? null : 'Enter WE Code'"
                                :readonly="$readonly" class="w-full" />
                            <flux:error name="we_code" />
                        </flux:field>

                        <flux:field>
                            <flux:label>2G Cells *</flux:label>
                            <flux:input wire:model="2G_cells" type="text" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter 2G Cells'" class="w-full" />
                            <flux:error name="2G_cells" />
                        </flux:field>
                        <flux:field>
                            <flux:label>3G Cells *</flux:label>
                            <flux:input wire:model="3G_cells" type="text" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter 3G Cells'" class="w-full" />
                            <flux:error name="3G_cells" />
                        </flux:field>
                        <flux:field>
                            <flux:label>4G Cells *</flux:label>
                            <flux:input wire:model="4G_cells" type="text" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter 4G Cells'" class="w-full" />
                            <flux:error name="4G_cells" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Status *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                <flux:select wire:model="status" type="text" placeholder="Enter Status"
                                    class="w-full">
                                    @foreach (('App\Enums\Status')::cases() as $status)
                                        <flux:select.option :value="$status->value">{{ $status->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select>
                            @else
                                <flux:input wire:model="status" :placeholder="$readonly ? null : 'Enter Site Status'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="status" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Zone *</flux:label>
                            @if ($target == 'Update' || $target == 'Create')
                                <flux:select wire:model="zone" type="text" placeholder="Enter Zone"
                                    class="w-full">
                                    @foreach (('App\Enums\Zone')::cases() as $zone)
                                        <flux:select.option :value="$zone->value">{{ $zone->name }}
                                        </flux:select.option>
                                    @endforeach

                                </flux:select>
                            @else
                                <flux:input wire:model="zone" :placeholder="$readonly ? null : 'Enter Site zone'"
                                    :readonly="$readonly" class="w-full" />
                            @endif

                            <flux:error name="zone" />
                        </flux:field>



                    </div>





                </div>
              @if ($target=='Update')
                    <flux:button variant="primary" color="zinc" wire:click='updateSite' class=" cursor-pointer">Update</flux:button>
              @endif
               @if ($target=='Create')
                    <flux:button variant="primary" color="zinc" wire:click='createSite' class=" cursor-pointer">Create</flux:button>
              @endif
              
            </form>


        </div>
    </div>
</div>
