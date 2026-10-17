<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        {{-- <x-ui.heading size="lg"> {{ $target }} Battery Record</x-ui.heading> --}}
                        <x-ui.heading size="lg"> {{ $site->site_code }} - {{ $site->site_name }} </x-ui.heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <x-ui.field>
                            <x-ui.label>Title *</x-ui.label>
                            <x-ui.input wire:model="form.title" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter power source'" class="w-full" />
                            <x-ui.error name="form.title" />
                        </x-ui.field>



                        <x-ui.field>
                            <x-ui.label>Notice Type *</x-ui.label>
                            @if ($target == 'Update' || $target == 'New')


                            <x-select-named-options wire:model="form.site_notice_type_id" :options="$siteNoticeTypes" class="w-full"
                                placeholder="Enter notice type" />
                            @else 
                            <x-ui.input wire:model="form.site_notice_type_id"
                                    :placeholder="$readonly ? null : 'Enter Site Status'" :readonly="$readonly"
                                    class="w-full" /> 
                             @endif
                            {{-- <x-ui.error name="form.site_notice_type_id" /> --}}
                        </x-ui.field>

                        <x-ui.field>
                            <x-ui.label>Solved*</x-ui.label>
                            {{-- @if ($target == 'Update' || $target == 'New') --}}
                            <x-ui.select wire:model="form.is_solved" type="text" placeholder="Is Solved?"
                                class="w-full">

                                <x-ui.select.option :value="0">No
                                </x-ui.select.option>
                                <x-ui.select.option :value="1">Yes
                                </x-ui.select.option>


                            </x-ui.select>
                            {{-- @else --}}
                            {{-- <x-ui.input wire:model="form.is_solved" :readonly="$readonly" class="w-full" /> --}}
                            {{-- @endif --}}

                            <x-ui.error name="form.is_solved" />
                        </x-ui.field>


                        <x-ui.field>

                            {{-- @if ($target == 'Update' || $target == 'New') --}}
                            <x-ui.textarea label="Description *" placeholder="Action descriptions..." rows="4"
                                wire:model="form.description" class="w-full" />
                            {{-- @else --}}
                            {{-- <x-ui.textarea label="Description *" wire:model="description" rows="4"
                                :placeholder="$readonly ? null : 'Action descriptions...'" :readonly="$readonly"
                                class="w-full" /> --}}
                            {{-- @endif --}}

                          
                        </x-ui.field>




                    </div>






                </div>
                @if ($target == 'Update')
                    <x-ui.button variant="primary" color="zinc" wire:click='updateSiteNotice' class=" cursor-pointer">
                        Update</x-ui.button>
                @endif
                @if ($target == 'New')
                    <x-ui.button variant="primary" color="zinc" wire:click='newSiteNotice' class=" cursor-pointer">
                        Create</x-ui.button>
                @endif

            </form>


        </div>
    </div>
</div>
