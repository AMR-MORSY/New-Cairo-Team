<div>
    <x-auth-header :title="__('Forgot password')"
        :description="__('Enter your email and we will send you a reset link.')" />

    <x-auth-session-status :status="session('status')" />

    <form method="POST" wire:submit="sendPasswordResetLink" class="grid gap-5">
        <x-ui.input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autofocus
            placeholder="email@example.com"
        />

        <x-ui.button type="submit" variant="primary" block class="mt-1">
            <span wire:loading.remove wire:target="sendPasswordResetLink">{{ __('Email password reset link') }}</span>
            <span wire:loading wire:target="sendPasswordResetLink" class="loading loading-spinner loading-sm"></span>
        </x-ui.button>
    </form>

    <p class="mt-8 text-center text-sm text-base-content/60">
        {{ __('Or, return to') }}
        <x-ui.link :href="route('home')" wire:navigate variant="primary">{{ __('sign in') }}</x-ui.link>
    </p>
</div>
