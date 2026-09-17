<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\CharacterBuildController;
use App\Http\Controllers\RaceController;
Route::middleware('auth')->name('character.')->prefix('character')->group(function () {

    Route::post('/', [CharacterController::class, 'store'])->name('store');
    Route::get('/index', [CharacterController::class, 'index'])->name('index');
    Route::get('/create', [CharacterController::class, 'create'])->name('create');

    Route::patch('/{character}/race', [CharacterBuildController::class, 'race'])
        ->whereNumber('character')->name('race.update');
    Route::put('/{character}/classes', [CharacterBuildController::class, 'gameClass'])
        ->whereNumber('character')->name('classes.update');
    Route::delete('/{character}/classes/{selection}', [CharacterBuildController::class, 'removeClass'])
        ->whereNumber(['character', 'selection'])->name('classes.destroy');

    Route::get('/{character}/workshop', [CharacterController::class, 'workshop'])
    ->whereNumber('character')
    ->name('workshop');

    Route::post('/{character}/messages', [CharacterController::class, 'storeMessage'])
    ->whereNumber('character')
    ->name('messages.store');

});

