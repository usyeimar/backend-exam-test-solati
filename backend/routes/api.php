<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->as('v1.')
    ->group(function () {

        require __DIR__.'/auth/v1.php';

        Route::middleware(['auth:api'])
            ->group(function () {
                require __DIR__.'/tasks/v1.php';
                require __DIR__.'/user/v1.php';
            });
    });
