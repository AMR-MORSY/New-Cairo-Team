<section>
    <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <!-- Table Header -->
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                            Role
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                            Permissions
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                            Team
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                            Zone
                        </th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                    @foreach ($rolesAndPermissions as $index => $rolePermissions)
                        <tr
                            class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200 {{ $index % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }}">

                            <!-- Role Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div
                                            class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $rolePermissions['role'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>


                            <!-- Permissions Column -->
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($rolePermissions['permissions'] as $permission)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $permission }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>


                        </tr>
                    @endforeach

                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                            </div>
                        </td>
                        <!--Team Column-->
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                @if ($teamAndZone['team'])
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div
                                                class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                {{-- <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" 
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg> --}}
                                                <flux:icon.trash wire:click="deleteTeam" class=" cursor-pointer text-red-700" />
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $teamAndZone['team']['code'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </td>
                        <!--Zone Column-->
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                @if ($teamAndZone['zone'])
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div
                                                class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $teamAndZone['zone']['code'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endif


                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>






        </div>




    </div>


    <div class="tabs tabs-border mt-7">
        {{--  /////////////////////////////////////////Site Details view//////////////////////////////////// --}}
        <input type="radio" name="my_tabs_2" class="tab" aria-label="Assign Role" checked="checked" />


        <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">

            <section class="w-full">
                <x-viewLayouts.main-view-layout :heading="__('New Role')" :subheading="__('Assign Role to a user. ')">
                    {{-- <x-slot:links>
                            <x-site-navlinks-group :site="$site" />
                        </x-slot:links> --}}

                    {{-- <x-site-creation-form target="Information" :readonly="true" /> --}}


                    <form class="p-6 space-y-8" wire:submit="updateRole">
                        <flux:field>
                            <flux:label>Role *</flux:label>
                            <x-select wire:model="newRoles"
                                placeholder="More than one role could be assigned to the user" multiselect
                                :options="$teamRoles" option-label="name" option-value="name" />
                        </flux:field>

                        <flux:button variant="primary" color="zinc" type="submit" class=" cursor-pointer">
                            Update</flux:button>

                    </form>

                </x-viewLayouts.main-view-layout>
            </section>
        </div>



        {{-- //////////////////////////direct cascades view///////////////////////////////  --}}

        <input type="radio" name="my_tabs_2" class="tab" aria-label="Update Zone & Team" />

        <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">
            <section class="w-full">
                <x-viewLayouts.main-view-layout :heading="__('New Zone & Team')" :subheading="__('Changing the user assigned zone or team will revoke all roles assigned to that user. ')">
                    {{-- <x-slot:links>
                            <x-site-navlinks-group :site="$site" />
                        </x-slot:links> --}}

                    {{-- <x-site-creation-form target="Information" :readonly="true" /> --}}


                    <form class="p-6 space-y-8" wire:submit="updateZoneAndTeam">
                        <flux:label>Zone *</flux:label>
                        <x-select wire:model="newZone" placeholder="Select the corresponding zone" :options="$zones"
                            option-label="code" option-value="id" />

                        <flux:label>Team *</flux:label>
                        <x-select wire:model.live="newTeam"
                            placeholder="Select the team and the corresponding zones will be showed " :options="$teams"
                            option-label="code" option-value="id" />


                        <flux:button variant="primary" color="zinc" type="submit" class=" cursor-pointer">
                            Update</flux:button>

                    </form>

                </x-viewLayouts.main-view-layout>
            </section>
        </div>





    </div>

</section>
