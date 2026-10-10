<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        {{-- window.appearance is defined by the bootstrap script in partials/head;
             it writes localStorage and repaints both the dark class and data-theme. --}}
        <div class="join" x-data="{ choice: window.appearance.current }"
            x-on:appearance-changed.window="choice = $event.detail">

            @foreach ([
                'light' => ['sun', __('Light')],
                'dark' => ['moon', __('Dark')],
                'system' => ['computer-desktop', __('System')],
            ] as $value => [$icon, $label])
                <label class="btn join-item gap-2"
                    x-bind:class="choice === '{{ $value }}' ? 'btn-active btn-primary' : ''">
                    <input type="radio" name="appearance" value="{{ $value }}" class="sr-only"
                        x-model="choice" x-on:change="window.appearance.set('{{ $value }}')" />
                    <x-dynamic-component :component="'icons.' . $icon" class="size-4" />
                    {{ $label }}
                </label>
            @endforeach
        </div>
    </x-settings.layout>
</section>
