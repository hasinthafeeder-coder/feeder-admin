<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('logout', [LogoutController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
