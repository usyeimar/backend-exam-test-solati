<?php

use App\Http\Controllers\API\User\MeController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')
    ->as('user.')
    ->group(function () {

        Route::get('/', MeController::class)
            ->name('me');
    });
