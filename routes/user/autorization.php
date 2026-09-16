<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

Route::get('/',[UserController::class,'login']);
Route::get('/login',[UserController::class,'login'])->name('login');
Route::get('/signup', [UserController::class, 'signup'])->name('signup');
Route::post('/signup', [UserController::class, 'create'])->name('signup.create');
Route::post('/login', [UserController::class, 'find'])->name('login.find');
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');