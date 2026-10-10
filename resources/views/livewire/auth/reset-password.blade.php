<div>
    <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below.')" />

    <x-auth-session-status :status="session('status')" />

    {{-- a dead or used link: nothing on the form can fix it, so it is said once, up here --}}
    @error('resetLink')
        <x-ui.callout variant="danger" icon="exclamation-triangle" class="mb-6">{{ $message }}</x-ui.callout>
    @enderror

    <form method="POST" wire:submit="resetPassword" class="grid gap-5">
        <x-ui.input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

        <x-ui.input wire:model="password" :label="__('Password')" type="password" required
            autocomplete="new-password" :placeholder="__('Password')" viewable />

        <x-ui.input wire:model="password_confirmation" :label="__('Confirm password')" type="password" required
            autocomplete="new-password" :placeholder="__('Confirm password')" viewable />

        <x-ui.button type="submit" variant="primary" block class="mt-1">
            <span wire:loading.remove wire:target="resetPassword">{{ __('Reset password') }}</span>
            <span wire:loading wire:target="resetPassword" class="loading loading-spinner loading-sm"></span>
        </x-ui.button>
    </form>
</div>
