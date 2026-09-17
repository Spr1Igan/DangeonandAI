<?php

use App\Http\Controllers\RaceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('race')->name('race.')->group(function () {
    Route::get('/index', [RaceController::class, 'index'])->name('index');
    Route::get('/create', [RaceController::class, 'create'])->name('create');
    Route::post('/', [RaceController::class, 'store'])->name('store');
    Route::get('/{race}', [RaceController::class, 'show'])->whereNumber('race')->name('show');
    Route::get('/{race}/versions/{version}/edit', [RaceController::class, 'edit'])
        ->whereNumber(['race', 'version'])->name('versions.edit');
    Route::patch('/{race}/versions/{version}', [RaceController::class, 'update'])
        ->whereNumber(['race', 'version'])->name('versions.update');
    Route::post('/{race}/versions/{version}/finalize', [RaceController::class, 'finalize'])
        ->whereNumber(['race', 'version'])->name('versions.finalize');
    Route::post('/{race}/versions/{version}/copy', [RaceController::class, 'createVersion'])
        ->whereNumber(['race', 'version'])->name('versions.copy');
    Route::post('/{race}/archive', [RaceController::class, 'archive'])
        ->whereNumber('race')->name('archive');
    Route::post('/{race}/restore', [RaceController::class, 'restore'])
        ->whereNumber('race')->name('restore');
});
