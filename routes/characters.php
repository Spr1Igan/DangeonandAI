<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;

Route::middleware('auth')->name('character.')->prefix('character')->group(function () {

    Route::post('/', [CharacterController::class, 'store'])->name('store');
    Route::get('/index', [CharacterController::class, 'index'])->name('index');
    Route::get('/create', [CharacterController::class, 'create'])->name('create');

    Route::get('/{character}/workshop', [CharacterController::class, 'workshop'])
    ->whereNumber('character')
    ->name('workshop');

    Route::post('/{character}/messages', [CharacterController::class, 'storeMessage'])
    ->whereNumber('character')
    ->name('messages.store');
});