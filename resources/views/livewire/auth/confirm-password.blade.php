<div>
    <x-auth-header :title="__('Confirm password')"
        :description="__('This is a secure area. Please confirm your password before continuing.')" />

    <x-auth-session-status :status="session('status')" />

    <form method="POST" wire:submit="confirmPassword" class="grid gap-5">
        <x-ui.input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="current-password"
            :placeholder="__('Password')"
            viewable
        />

        <x-ui.button type="submit" variant="primary" block class="mt-1">
            <span wire:loading.remove wire:target="confirmPassword">{{ __('Confirm') }}</span>
            <span wire:loading wire:target="confirmPassword" class="loading loading-spinner loading-sm"></span>
        </x-ui.button>
    </form>
</div>
