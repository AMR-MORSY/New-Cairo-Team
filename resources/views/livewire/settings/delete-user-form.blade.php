<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <x-ui.heading>{{ __('Delete account') }}</x-ui.heading>
        <x-ui.subheading>{{ __('Delete your account and all of its resources') }}</x-ui.subheading>
    </div>

    <x-ui.modal.trigger name="confirm-user-deletion">
        <x-ui.button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Delete account') }}
        </x-ui.button>
    </x-ui.modal.trigger>

    <x-ui.modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form method="POST" wire:submit="deleteUser" class="space-y-6">
            <div>
                <x-ui.heading size="lg">{{ __('Are you sure you want to delete your account?') }}</x-ui.heading>

                <x-ui.subheading>
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </x-ui.subheading>
            </div>

            <x-ui.input wire:model="password" :label="__('Password')" type="password" />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <x-ui.modal.close>
                    <x-ui.button variant="filled">{{ __('Cancel') }}</x-ui.button>
                </x-ui.modal.close>

                <x-ui.button variant="danger" type="submit">{{ __('Delete account') }}</x-ui.button>
            </div>
        </form>
    </x-ui.modal>
</section>
