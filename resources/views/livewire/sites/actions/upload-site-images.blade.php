<div>
    <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class="mt-5 overflow-y-hidden">
        <x-ui.callout.heading>{{ $site->site_code }} - {{ $site->site_name }}</x-ui.callout.heading>
        <x-ui.callout.text>Up to five images could be uploaded (max 1MB each).</x-ui.callout.text>

        <form wire:submit.prevent="save">
            @error('photos')
                <span class="text-red-500 block mb-2">{{ $message }}</span>
            @enderror

            @error('photos.*')
                <span class="text-red-500 block mb-2">{{ $message }}</span>
            @enderror

            <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
                x-on:livewire-upload-error="uploading = false"
                x-on:livewire-upload-progress="progress = $event.detail.progress">

                <div class="flex items-center justify-between gap-6 flex-wrap">
                    <div class="mt-9">
                        <x-ui.field>

                            <x-ui.input type="file" wire:model="photos" multiple accept="image/*" />
                            <label class="label">Max size 1MB per image, up to 5 images</label>
                        </x-ui.field>

                        <x-ui.button variant="primary" type="submit" class="mt-4 cursor-pointer">
                            Save Photos
                        </x-ui.button>

                        <!-- Progress Bar -->
                        <div x-show="uploading" class="mt-4">
                            <progress max="100" x-bind:value="progress" class="w-full"></progress>
                            <p class="text-sm text-gray-600 mt-1">Uploading: <span x-text="progress"></span>%</p>
                        </div>
                    </div>

                    <!-- Preview Section -->
                    @if ($photos)
                        <div class="flex gap-6 flex-wrap">
                            @foreach ($photos as $photo)
                                <div class="flex flex-col items-center justify-between border rounded p-2">
                                    <div class="w-32 h-32 ">
                                        <img src="{{ $photo->temporaryUrl() }}"
                                            class="w-full h-full object-cover rounded">
                                    </div>

                                    <div class="text-xs w-32 max-w-7 text-center  mt-1">
                                        {{ $photo->getClientOriginalName() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </x-ui.callout>
</div>
