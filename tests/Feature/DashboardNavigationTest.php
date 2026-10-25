<?php

use App\Models\User;

/*
 * The app navigates with wire:navigate, which re-runs every body <script> of
 * the new page unless it is marked data-navigate-once. Filament's scripts
 * must be, or a second run of support.js leaves the dashboard's x-load chart
 * components unloaded until a full refresh.
 */
it('marks Filament scripts to run once across wire:navigate', function () {
    $html = $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertOk()
        ->getContent();

    preg_match_all('/<script[^>]+src="[^"]*\/js\/filament\/[^"]*"[^>]*>/', $html, $tags);

    expect($tags[0])->not->toBeEmpty();

    foreach ($tags[0] as $tag) {
        expect($tag)->toContain('data-navigate-once');
    }
});

/*
 * The auth layout loads no Filament scripts. Navigating from it into the app
 * would inject them after Alpine has started, too late to register x-load,
 * so the dashboard charts stayed blank until a refresh.
 */
it('signs in with a full page load, not wire:navigate', function () {
    $user = User::factory()->create();

    $login = \Livewire\Livewire::test(\App\Livewire\Auth\Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login')
        ->assertRedirect(route('dashboard', absolute: false));

    expect($login->effects['redirectUsingNavigate'] ?? false)->toBeFalse();
});
