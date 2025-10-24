    <x-viewLayouts.main-view-layout :heading="__('Site Notes')" :subheading="__('You will find very important information about the batteries in site ') .
        $site->site_code .
        '-' .
        $site->site_name">


        <x-slot:links>
            <livewire:sites.site-notes-links :site="$site" />
        </x-slot:links>
        @if (count($notices) > 0)



            @foreach ($notices as $notice)
                <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                    <flux:callout.heading>{{ $notice->title }}</flux:callout.heading>
                    <flux:callout.text color="blue">{{ $notice->formatted_created_at }}</flux:callout.text>
                    <flux:callout.text>{{ $notice->description }}</flux:callout.text>
                    @if ($notice->is_solved)
                        <flux:text color='green'>Solved</flux:callout.text>
                        @else
                            <flux:text color='red'>Not Solved</flux:callout.text>
                    @endif
                    <x-slot name="actions">
                        <flux:button :href="route('site.note.update',$notice->id)" wire:navigate class=" cursor-pointer">
                            UPDATE
                        </flux:button>
                        <flux:button wire:key="delete-btn-{{ $notice->id }}" wire:click="delete({{ $notice->id }})"
                            class=" cursor-pointer"
                            >DELETE
                        </flux:button>
                    </x-slot>
                </flux:callout>
            @endforeach



            <div class=" mt-5">
                {{ $notices->links() }}
            </div>
        @else
            <flux:callout icon="exclamation-triangle" variant="secondary" inline class=" mt-5">
                <flux:callout.heading>No Notes Found on this site</flux:callout.heading>
                <flux:callout.text>click the button to go to new record creation page.</flux:callout.text>
                <x-slot name="actions">
                    <flux:button :href="route('site.note.create',$site->site_code)" wire:navigate>NEW NOTE
                    </flux:button>

                </x-slot>
            </flux:callout>
        @endif
    </x-viewLayouts.main-view-layout>
