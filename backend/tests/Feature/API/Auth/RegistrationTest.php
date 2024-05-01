<?php

use function Pest\Laravel\postJson;
beforeEach(function () {
    passport();
});

test('new users can register', function () {
    $response = postJson('/api/v1/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertCreated();

    $response->assertJsonStructure([
        'data' => [
            'token' => [
                'value',
                'expires_at',
                'expires_in',
            ]
        ],
    ]);
});
