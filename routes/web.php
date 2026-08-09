<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileProxyController;
use App\Http\Controllers\Reseller\ResellerController;
use App\Http\Controllers\Reseller\ResellerApprovalController;
use App\Http\Controllers\Company\CompanyBankAccountController;

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.main.dashboard');
    })
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    // Reseller Management Routes
    Route::prefix('resellers')
        ->name('resellers.')
        ->group(function () {

            Route::get('/', [ResellerController::class, 'index'])
                ->middleware('permission:resellers.view')
                ->name('index');


            Route::get('/{user}', [ResellerController::class, 'show'])
                ->middleware('permission:resellers.view')
                ->name('show');


            Route::post('/{user}/approve', [ResellerApprovalController::class, 'approve'])
                ->middleware('permission:resellers.approve')
                ->name('approve');


            Route::post('/{user}/reject', [ResellerApprovalController::class, 'reject'])
                ->middleware('permission:resellers.reject')
                ->name('reject');


            Route::post('/{user}/suspend', [ResellerApprovalController::class, 'suspend'])
                ->middleware('permission:resellers.suspend')
                ->name('suspend');


            Route::post('/{user}/activate', [ResellerApprovalController::class, 'activate'])
                ->middleware('permission:resellers.approve')
                ->name('activate');


            Route::post('/{user}/delete', [ResellerApprovalController::class, 'delete'])
                ->middleware('permission:resellers.reject')
                ->name('delete');
        });

    Route::prefix('companies')
        ->name('companies.')
        ->middleware('permission:companies.view')
        ->group(function () {

            Route::post(
                '/{company}/bank-accounts',
                [CompanyBankAccountController::class, 'store']
            )
                ->middleware('permission:companies.update')
                ->name('bank-accounts.store');


            Route::put(
                '/bank-accounts/{bankAccount}',
                [CompanyBankAccountController::class, 'update']
            )
                ->middleware('permission:companies.update')
                ->name('bank-accounts.update');


            Route::delete(
                '/bank-accounts/{bankAccount}',
                [CompanyBankAccountController::class, 'destroy']
            )
                ->middleware('permission:companies.update')
                ->name('bank-accounts.destroy');
        });

    Route::get('/files/{uuid}/thumbnail/{size?}', [FileProxyController::class, 'thumbnail'])
        ->where([
            'uuid' => '[A-Za-z0-9]+',
            'size' => 'sm|md|lg',
        ])
        ->name('files.thumbnail');
});
