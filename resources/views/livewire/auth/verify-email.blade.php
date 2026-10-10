<div>
    <x-auth-header :title="__('Verify your email')"
        :description="__('Please verify your email address by clicking the link we just sent you.')" />

    @if (session('status') === 'verification-link-sent')
        <x-ui.callout variant="success" class="mb-6">
            {{ __('A new verification link has been sent to your email address.') }}
        </x-ui.callout>
    @endif

    <div class="grid gap-4">
        <x-ui.button wire:click="sendVerification" variant="primary" block>
            <span wire:loading.remove wire:target="sendVerification">{{ __('Resend verification email') }}</span>
            <span wire:loading wire:target="sendVerification" class="loading loading-spinner loading-sm"></span>
        </x-ui.button>

        <x-ui.link wire:click="logout" class="cursor-pointer text-center text-sm">{{ __('Log out') }}</x-ui.link>
    </div>
</div>
