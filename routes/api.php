<?php

use App\Http\Controllers\DirectoryManagerController;
use App\Http\Controllers\FileManagerController;
use Illuminate\Support\Facades\Route;

Route::controller(DirectoryManagerController::class)
    ->prefix('dir')
    ->group(function () {
        Route::post('dir', 'createDirectory')
            ->name('create.dir');
        Route::delete('dir',  'deleteDirectory')
            ->name('delete.dir');
        Route::patch('dir',  'renameDirectory')
            ->name('rename.dir');
    });

Route::controller(FileManagerController::class)
    ->group(function () {
        Route::post('upload', 'uploadFiles')
            ->name('upload-files');
    });
