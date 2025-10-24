<div class=" bg-gray-50 py-8 w-full">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            <!-- Form -->
            <form class="p-6 space-y-8">

                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-2">

                        {{-- <flux:heading size="lg"> {{ $target }} Battery Record</flux:heading> --}}
                        <flux:heading size="lg"> {{ $site->site_code }} - {{ $site->site_name }} </flux:heading>


                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2  2xl:grid-cols-3 gap-6">
                        <flux:field>
                            <flux:label>Title *</flux:label>
                            <flux:input wire:model="form.title" :readonly="$readonly"
                                :placeholder="$readonly ? null : 'Enter power source'" class="w-full" />
                            <flux:error name="form.title" />
                        </flux:field>



                        <flux:field>
                            <flux:label>Notice Type *</flux:label>
                            {{-- @if ($target == 'Update' || $target == 'New') --}}


                            <x-select-enums wire:model="form.notice_type" :enumOptions="('App\Enums\SiteNoticeTypes')::cases()" class="w-full"
                                placeholder="Enter the Status" />
                            {{-- @else --}}
                            {{-- <flux:input wire:model="form.notice_type"
                                    :placeholder="$readonly ? null : 'Enter Site Status'" :readonly="$readonly"
                                    class="w-full" /> --}}
                            {{-- @endif --}}
                            <flux:error name="form.notice_type" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Solved*</flux:label>
                            {{-- @if ($target == 'Update' || $target == 'New') --}}
                            <flux:select wire:model="form.is_solved" type="text" placeholder="Is Solved?"
                                class="w-full">

                                <flux:select.option :value="0">No
                                </flux:select.option>
                                <flux:select.option :value="1">Yes
                                </flux:select.option>


                            </flux:select>
                            {{-- @else --}}
                            {{-- <flux:input wire:model="form.is_solved" :readonly="$readonly" class="w-full" /> --}}
                            {{-- @endif --}}

                            <flux:error name="form.is_solved" />
                        </flux:field>


                        <flux:field>

                            {{-- @if ($target == 'Update' || $target == 'New') --}}
                            <flux:textarea label="Description *" placeholder="Action descriptions..." rows="4"
                                wire:model="form.description" class="w-full" />
                            {{-- @else --}}
                            {{-- <flux:textarea label="Description *" wire:model="description" rows="4"
                                :placeholder="$readonly ? null : 'Action descriptions...'" :readonly="$readonly"
                                class="w-full" /> --}}
                            {{-- @endif --}}

                          
                        </flux:field>




                    </div>






                </div>
                @if ($target == 'Update')
                    <flux:button variant="primary" color="zinc" wire:click='updateSiteNotice' class=" cursor-pointer">
                        Update</flux:button>
                @endif
                @if ($target == 'New')
                    <flux:button variant="primary" color="zinc" wire:click='newSiteNotice' class=" cursor-pointer">
                        Create</flux:button>
                @endif

            </form>


        </div>
    </div>
</div>
