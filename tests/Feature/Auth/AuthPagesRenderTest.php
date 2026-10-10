<?php

use App\Models\User;

test('every guest auth page renders', function (string $uri) {
    $this->get($uri)->assertStatus(200);
})->with(['/', '/register', '/forgot-password', '/reset-password/tok3n']);

test('authenticated auth pages render', function (string $uri) {
    $this->actingAs(User::factory()->unverified()->create());
    $this->get($uri)->assertStatus(200);
})->with(['/verify-email', '/confirm-password']);
