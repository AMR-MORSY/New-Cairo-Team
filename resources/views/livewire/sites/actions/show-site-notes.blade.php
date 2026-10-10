    <x-viewLayouts.main-view-layout :heading="__('Site Notes')" :subheading="__('You will find very important information about the batteries in site ') .
        $site->site_code .
        '-' .
        $site->site_name">


        <x-slot:links>
            <livewire:sites.site-notes-links :site="$site" />
        </x-slot:links>
        @if (count($notices) > 0)



            @foreach ($notices as $notice)
                <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                    <x-ui.callout.heading>{{ $notice->title }}</x-ui.callout.heading>
                    <x-ui.callout.text color="blue">{{ $notice->formatted_created_at }}</x-ui.callout.text>
                    <x-ui.callout.text>{{ $notice->description }}</x-ui.callout.text>
                    @if ($notice->is_solved)
                        <x-ui.text color='green'>Solved</x-ui.callout.text>
                        @else
                            <x-ui.text color='red'>Not Solved</x-ui.callout.text>
                    @endif
                    <x-slot name="actions">
                        <x-ui.button :href="route('site.note.update',$notice->id)" wire:navigate class=" cursor-pointer">
                            UPDATE
                        </x-ui.button>
                        <x-ui.modal.trigger name="delete-note-{{ $notice->id }}">
                            <x-ui.button wire:key="delete-btn-{{ $notice->id }}" class=" cursor-pointer">DELETE
                            </x-ui.button>
                        </x-ui.modal.trigger>
                    </x-slot>
                </x-ui.callout>

                <x-ui.modal name="delete-note-{{ $notice->id }}" wire:key="delete-modal-{{ $notice->id }}">
                    <x-ui.heading size="lg">{{ __('Delete this note?') }}</x-ui.heading>
                    <x-ui.text class="mt-2">{{ $notice->title }}</x-ui.text>
                    <x-ui.text class="mt-1">{{ __('This cannot be undone.') }}</x-ui.text>

                    <div class="modal-action">
                        <x-ui.modal.close>
                            <x-ui.button variant="ghost">{{ __('Cancel') }}</x-ui.button>
                        </x-ui.modal.close>
                        <x-ui.button variant="danger" wire:click="deleteNote({{ $notice->id }})"
                            loading="deleteNote({{ $notice->id }})">{{ __('Yes, delete it') }}</x-ui.button>
                    </div>
                </x-ui.modal>
            @endforeach



            <div class=" mt-5">
                {{ $notices->links() }}
            </div>
        @else
            <x-ui.callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                <x-ui.callout.heading>No Notes Found on this site</x-ui.callout.heading>
                <x-ui.callout.text>click the button to go to new record creation page.</x-ui.callout.text>
                <x-slot name="actions">
                    <x-ui.button :href="route('site.note.create',$site->site_code)" wire:navigate>NEW NOTE
                    </x-ui.button>

                </x-slot>
            </x-ui.callout>
        @endif
    </x-viewLayouts.main-view-layout>
