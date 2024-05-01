<?php

use App\Http\Controllers\API\Auth\AuthenticatedSessionController;
use App\Http\Controllers\API\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\NewPasswordController;
use App\Http\Controllers\API\Auth\PasswordResetLinkController;
use App\Http\Controllers\API\Auth\RegisteredUserController;
use App\Http\Controllers\API\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->as('auth.')
    ->group(function () {
        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware('guest')
            ->name('register');

        Route::post('/login', LoginController::class)->name('login');

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->middleware('guest')
            ->name('password.email');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->middleware('guest')
            ->name('password.store');

        Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['auth', 'signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['auth', 'throttle:6,1'])
            ->name('verification.send');

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->middleware('auth')
            ->name('logout');
    });


Route::prefix('oauth')
    ->as('passport.')
    ->namespace('\Laravel\Passport\Http\Controllers')
    ->group(function () {
        Route::post('/token', [
            'uses' => 'AccessTokenController@issueToken',
            'as' => 'token',
            'middleware' => 'throttle',
        ]);

        Route::get('/authorize', [
            'uses' => 'AuthorizationController@authorize',
            'as' => 'authorizations.authorize',
            'middleware' => 'web',
        ]);

        Route::middleware(['auth:api'])->group(function () {
            Route::post('/token/refresh', [
                'uses' => 'TransientTokenController@refresh',
                'as' => 'token.refresh',
            ]);

            Route::post('/authorize', [
                'uses' => 'ApproveAuthorizationController@approve',
                'as' => 'authorizations.approve',
            ]);

            Route::delete('/authorize', [
                'uses' => 'DenyAuthorizationController@deny',
                'as' => 'authorizations.deny',
            ]);

            Route::get('/tokens', [
                'uses' => 'AuthorizedAccessTokenController@forUser',
                'as' => 'tokens.index',
            ]);

            Route::delete('/tokens/{token_id}', [
                'uses' => 'AuthorizedAccessTokenController@destroy',
                'as' => 'tokens.destroy',
            ]);

            Route::get('/clients', [
                'uses' => 'ClientController@forUser',
                'as' => 'clients.index',
            ]);

            Route::post('/clients', [
                'uses' => 'ClientController@store',
                'as' => 'clients.store',
            ]);

            Route::put('/clients/{client_id}', [
                'uses' => 'ClientController@update',
                'as' => 'clients.update',
            ]);

            Route::delete('/clients/{client_id}', [
                'uses' => 'ClientController@destroy',
                'as' => 'clients.destroy',
            ]);

            Route::get('/scopes', [
                'uses' => 'ScopeController@all',
                'as' => 'scopes.index',
            ]);

            Route::get('/personal-access-tokens', [
                'uses' => 'PersonalAccessTokenController@forUser',
                'as' => 'personal.tokens.index',
            ]);

            Route::post('/personal-access-tokens', [
                'uses' => 'PersonalAccessTokenController@store',
                'as' => 'personal.tokens.store',
            ]);

            Route::delete('/personal-access-tokens/{token_id}', [
                'uses' => 'PersonalAccessTokenController@destroy',
                'as' => 'personal.tokens.destroy',
            ]);
        });
    });
