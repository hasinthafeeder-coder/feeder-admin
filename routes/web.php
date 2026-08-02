<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileProxyController;
use App\Http\Controllers\Reseller\ResellerController;
use App\Http\Controllers\Reseller\ResellerApprovalController;

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.main.dashboard');
    })->name('dashboard');

    // Reseller Management Routes
    Route::prefix('resellers')
        ->name('resellers.')
        ->group(function () {
            Route::get('/', [ResellerController::class, 'index'])
                ->name('index');

            Route::get('/{user}', [ResellerController::class, 'show'])
                ->name('show');

            Route::post('/{user}/approve', [ResellerApprovalController::class, 'approve'])
                ->name('approve');

            Route::post('/{user}/reject', [ResellerApprovalController::class, 'reject'])
                ->name('reject');

            Route::post('/{user}/suspend', [ResellerApprovalController::class, 'suspend'])
                ->name('suspend');

            Route::post('/{user}/activate', [ResellerApprovalController::class, 'activate'])
                ->name('activate');

            Route::post('/{user}/delete', [ResellerApprovalController::class, 'delete'])
                ->name('delete');
        });

    Route::get('/files/{uuid}/thumbnail/{size?}', [FileProxyController::class, 'thumbnail'])
        ->where([
            'uuid' => '[A-Za-z0-9]+',
            'size' => 'sm|md|lg',
        ])
        ->name('files.thumbnail');

});


// use App\Http\Controllers\ProfileController;
// use Illuminate\Support\Facades\Route;

// use Feeder\Core\Helpers\Test;

// // MAIN
// Route::get('main/dashboard', function () {
//     return view('pages.main.dashboard');
// })->name('main.dashboard');


// // PROFILE
// Route::get('reseller/profile', function () {
//     return view('pages.reseller.profile');
// })->name('reseller.profile');

// Route::get('reseller/list', function () {
//     return view('pages.reseller.list');
// })->name('reseller.list');

// Route::get('package-test', function () {
//     return Test::hello();
// });

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/test', function () {
//     return view('test_index');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__ . '/auth.php';
