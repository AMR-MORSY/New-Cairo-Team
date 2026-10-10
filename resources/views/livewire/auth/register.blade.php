<div>
    <x-auth-header :title="__('Create an account')"
        :description="__('Enter your details below to get started.')" />

    <x-auth-session-status :status="session('status')" />

    <form method="POST" wire:submit="register" class="grid gap-5">
        <x-ui.input wire:model="name" :label="__('Name')" type="text" required autofocus
            autocomplete="name" :placeholder="__('Full name')" />

        <x-ui.input wire:model="email" :label="__('Email address')" type="email" required
            autocomplete="email" placeholder="email@example.com" />

        <x-ui.input wire:model="password" :label="__('Password')" type="password" required
            autocomplete="new-password" :placeholder="__('Password')" viewable />

        <x-ui.input wire:model="password_confirmation" :label="__('Confirm password')" type="password" required
            autocomplete="new-password" :placeholder="__('Confirm password')" viewable />

        <x-ui.button type="submit" variant="primary" block class="mt-1">
            <span wire:loading.remove wire:target="register">{{ __('Create account') }}</span>
            <span wire:loading wire:target="register" class="loading loading-spinner loading-sm"></span>
        </x-ui.button>
    </form>

    <p class="mt-8 text-center text-sm text-base-content/60">
        {{ __('Already have an account?') }}
        <x-ui.link :href="route('home')" wire:navigate variant="primary">{{ __('Log in') }}</x-ui.link>
    </p>
</div>
