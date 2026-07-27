<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use Feeder\Core\Helpers\Test;

// MAIN
Route::get('main/dashboard', function () {
    return view('pages.main.dashboard');
})->name('main.dashboard');


// PROFILE
Route::get('reseller/profile', function () {
    return view('pages.reseller.profile');
})->name('reseller.profile');

Route::get('reseller/list', function () {
    return view('pages.reseller.list');
})->name('reseller.list');

Route::get('package-test', function () {
    return Test::hello();
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test_index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
