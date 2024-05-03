<?php

use Illuminate\Support\Facades\Notification;
use function Pest\Laravel\postJson;

beforeEach(function () {
    passport();
});

test('new users can register', function () {
    Notification::fake();
    $response = postJson('/api/v1/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertCreated();

    $response->assertCreated();

});
