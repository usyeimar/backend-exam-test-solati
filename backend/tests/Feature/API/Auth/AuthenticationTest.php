<?php

use App\Models\User;

use function Pest\Laravel\postJson;

beforeEach(function () {
    passport(); // Initialize passport for testing
});

test('it can create access token', function () {
    $user = User::factory()->create();
    postJson(
        route('v1.auth.login'),
        [
            'email' => $user->email,
            'password' => 'password',
        ]
    )->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'object',
                'name',
                'email',
                'email_verified_at',
                'token' => [
                    'value',
                    'expires_at',
                    'type',
                ],
            ],
        ]);
})->group('authentication');
