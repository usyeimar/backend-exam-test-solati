<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, RefreshDatabase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Crear Passport Personal Access Client.
 */
function passport(): void
{
    $client = new ClientRepository();

    $personalClient = $client->createPersonalAccessClient(
        null,
        'Test Personal Access Client',
        'http://localhost'
    );
    config()->set('passport.personal_access_client.id', $personalClient->id);
    config()->set('passport.personal_access_client.secret', $personalClient->secret);

    $passwordClient = $client->createPasswordGrantClient(
        null,
        'Test Password Grant Client',
        'http://localhost'
    );
    config()->set('passport.password_grant_client.id', $passwordClient->id);
    config()->set('passport.password_grant_client.secret', $passwordClient->secret);
}

function signIn(?User $user = null): User
{
    if (is_null($user)) {
        $user = User::factory()->create();

        $user->save();

        Passport::actingAs(
            $user,
            ['*'],
        );

        return $user;
    }

    Passport::actingAs(
        $user,
        ['*'],
    );

    return $user;
}
