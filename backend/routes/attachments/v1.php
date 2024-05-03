<?php


use App\Http\Controllers\API\Attachments\DestroyController;
use App\Http\Controllers\API\Attachments\DownloadController;
use App\Http\Controllers\API\Attachments\IndexController;
use App\Http\Controllers\API\Attachments\UploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('attachments')
    ->as('attachments.')
    ->group(function () {
        Route::get('/', IndexController::class)->name('index');
        Route::post('/upload', UploadController::class)->name('upload');
        Route::get('/download/{attachment_uuid}', DownloadController::class)->name('download');
        Route::delete('/{attachment_uuid}', DestroyController::class)->name('delete');
    });
