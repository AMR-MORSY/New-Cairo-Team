<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

test('the sign-in screen is the home page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'wrong-password')
        ->call('login');

    $response->assertHasErrors('email');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');

    $this->assertGuest();
});
test('the home page is guest-only', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/')->assertRedirect(route('dashboard', absolute: false));
});

test('the login route no longer exists', function () {
    expect(Route::has('login'))->toBeFalse();

    $this->get('/login')->assertNotFound();
});
