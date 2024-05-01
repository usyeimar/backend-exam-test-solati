<?php

use App\Http\Controllers\API\Task\IndexController;
use App\Http\Controllers\API\Task\StoreController;
use App\Http\Controllers\API\Task\ShowController;
use App\Http\Controllers\API\Task\UpdateController;
use App\Http\Controllers\API\Task\DestroyController;
use Illuminate\Support\Facades\Route;

Route::prefix('tasks')
    ->as('tasks.')

    ->group(function () {
        Route::get('/', IndexController::class)->name('index');
        Route::post('/', StoreController::class)->name('store');
        Route::get('/{task_uuid}', ShowController::class)->name('show');
        Route::patch('/{task_uuid}', UpdateController::class)->name('update');
        Route::delete('/{task_uuid}', DestroyController::class)->name('destroy');
    });
