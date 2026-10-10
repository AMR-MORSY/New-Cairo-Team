<div>
    <div class="mb-8">
        <x-ui.heading size="2xl" :level="1">{{ __('Sign in') }}</x-ui.heading>
        <x-ui.subheading class="mt-1.5">
            {{ __('Enter your email and password to continue.') }}
        </x-ui.subheading>
    </div>

    @if (session('status'))
        <x-ui.callout variant="success" class="mb-6">{{ session('status') }}</x-ui.callout>
    @endif

    <form method="POST" wire:submit="login" class="grid gap-5">
        <x-ui.input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="email@example.com"
        />

        <div class="grid gap-1.5">
            <x-ui.input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            @if (Route::has('password.request'))
                <x-ui.link :href="route('password.request')" wire:navigate class="justify-self-end text-sm">
                    {{ __('Forgot your password?') }}
                </x-ui.link>
            @endif
        </div>

        {{-- <x-ui.checkbox wire:model="remember" :label="__('Remember me')" /> --}}

        <x-ui.button type="submit" variant="primary" block class="mt-1">
            <span wire:loading.remove wire:target="login">{{ __('Log in') }}</span>
            <span wire:loading wire:target="login" class="loading loading-spinner loading-sm"></span>
        </x-ui.button>
    </form>

    {{-- @if (Route::has('register'))
        <p class="mt-8 text-center text-sm text-base-content/60">
            {{ __('Don\'t have an account?') }}
            <x-ui.link :href="route('register')" wire:navigate variant="primary">{{ __('Sign up') }}</x-ui.link>
        </p>
    @endif --}}
</div>
