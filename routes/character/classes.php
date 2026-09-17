<?php

use App\Http\Controllers\GameClassController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('class')->name('class.')->group(function () {
    Route::get('/index', [GameClassController::class, 'index'])->name('index');
    Route::get('/create', [GameClassController::class, 'create'])->name('create');
    Route::post('/', [GameClassController::class, 'store'])->name('store');
    Route::get('/{gameClass}', [GameClassController::class, 'show'])->whereNumber('gameClass')->name('show');
    Route::get('/{gameClass}/versions/{version}/edit', [GameClassController::class, 'edit'])
        ->whereNumber(['gameClass', 'version'])->name('versions.edit');
    Route::patch('/{gameClass}/versions/{version}', [GameClassController::class, 'update'])
        ->whereNumber(['gameClass', 'version'])->name('versions.update');
    Route::post('/{gameClass}/versions/{version}/finalize', [GameClassController::class, 'finalize'])
        ->whereNumber(['gameClass', 'version'])->name('versions.finalize');
    Route::post('/{gameClass}/versions/{version}/copy', [GameClassController::class, 'createVersion'])
        ->whereNumber(['gameClass', 'version'])->name('versions.copy');
    Route::post('/{gameClass}/archive', [GameClassController::class, 'archive'])
        ->whereNumber('gameClass')->name('archive');
    Route::post('/{gameClass}/restore', [GameClassController::class, 'restore'])
        ->whereNumber('gameClass')->name('restore');
});
