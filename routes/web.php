<?php

use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');


require __DIR__.'/user/autorization.php';
require __DIR__.'/characters.php';