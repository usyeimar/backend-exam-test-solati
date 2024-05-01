<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')
    ->as('v1.')
    ->group(function () {

        /**
         * Obtener usuario autenticado.
         *
         * Este endpoint permite obtener la información del usuario autenticado.
         */
        Route::get('/me', function (Request $request) {
            return $request->user();
        })->name('me');

        require __DIR__ . '/auth/v1.php';

        Route::middleware(['auth:api'])
            ->group(function () {
                require __DIR__ . '/tasks/v1.php';
            });
    });
