<?php

use App\Http\Controllers\Company\CompanyBankAccountController;
use App\Http\Controllers\FileProxyController;
use App\Http\Controllers\Product\ProductCategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Stock\StockController;
use App\Http\Controllers\Reseller\ResellerApprovalController;
use App\Http\Controllers\Reseller\ResellerBulkMarketController;
use App\Http\Controllers\Reseller\ResellerController;
use App\Http\Controllers\Reseller\ResellerFinancialController;
use App\Http\Controllers\Reseller\ResellerMarketAccessController;
use App\Http\Controllers\Reseller\ResellerSupplierAssignmentController;
use App\Http\Controllers\Settings\FinancialSettingsController;
use App\Http\Controllers\Supplier\SupplierApprovalController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Supplier\SupplierTypeController;
use App\Http\Controllers\Team\TeamTreeController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

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

            Route::post('/bulk/markets/grant', [ResellerBulkMarketController::class, 'grant'])
                ->middleware('permission:resellers.markets.update')
                ->name('bulk.markets.grant');

            Route::post('/bulk/markets/revoke', [ResellerBulkMarketController::class, 'revoke'])
                ->middleware('permission:resellers.markets.update')
                ->name('bulk.markets.revoke');

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

            Route::post('/{user}/referral/activate', [ResellerController::class, 'activateReferralLink'])
                ->middleware('permission:referrals.activate')
                ->name('referral.activate');

            Route::post('/{user}/referral/deactivate', [ResellerController::class, 'deactivateReferralLink'])
                ->middleware('permission:referrals.deactivate')
                ->name('referral.deactivate');

            Route::post('/{user}/financial/service-charge', [ResellerFinancialController::class, 'updateServiceCharge'])
                ->middleware('permission:resellers.financial.update')
                ->name('financial.service-charge.update');

            Route::put('/{user}/markets', [ResellerMarketAccessController::class, 'update'])
                ->middleware('permission:resellers.markets.update')
                ->name('markets.update');

            Route::delete('/{user}/financial/service-charge', [ResellerFinancialController::class, 'clearServiceCharge'])
                ->middleware('permission:resellers.financial.update')
                ->name('financial.service-charge.clear');

            Route::post('/{user}/suppliers', [ResellerSupplierAssignmentController::class, 'store'])
                ->middleware('permission:resellers.suppliers.assign')
                ->name('suppliers.store');

            Route::delete('/{user}/suppliers/{supplier}', [ResellerSupplierAssignmentController::class, 'destroy'])
                ->middleware('permission:resellers.suppliers.assign')
                ->name('suppliers.destroy');
        });

    Route::prefix('settings')
        ->name('settings.')
        ->middleware('permission:settings.view')
        ->group(function () {
            Route::get('/financial', [FinancialSettingsController::class, 'index'])
                ->name('financial');

            Route::post('/financial', [FinancialSettingsController::class, 'update'])
                ->middleware('permission:settings.financial.update')
                ->name('financial.update');
        });

    // Supplier Management Routes
    Route::prefix('suppliers')
        ->name('suppliers.')
        ->group(function () {

            Route::get('/', [SupplierController::class, 'index'])
                ->middleware('permission:suppliers.view')
                ->name('index');

            Route::get('/{user}', [SupplierController::class, 'show'])
                ->middleware('permission:suppliers.view')
                ->name('show');

            Route::put('/{user}/supplier-type', [SupplierTypeController::class, 'update'])
                ->middleware('permission:suppliers.approve')
                ->name('supplier-type.update');

            Route::post('/{user}/approve', [SupplierApprovalController::class, 'approve'])
                ->middleware('permission:suppliers.approve')
                ->name('approve');

            Route::post('/{user}/reject', [SupplierApprovalController::class, 'reject'])
                ->middleware('permission:suppliers.reject')
                ->name('reject');

            Route::post('/{user}/suspend', [SupplierApprovalController::class, 'suspend'])
                ->middleware('permission:suppliers.suspend')
                ->name('suspend');

            Route::post('/{user}/activate', [SupplierApprovalController::class, 'activate'])
                ->middleware('permission:suppliers.approve')
                ->name('activate');

            Route::post('/{user}/delete', [SupplierApprovalController::class, 'delete'])
                ->middleware('permission:suppliers.reject')
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

    Route::prefix('product-categories')
        ->name('product-categories.')
        ->group(function () {
            Route::get('/', [ProductCategoryController::class, 'index'])
                ->middleware('permission:product_categories.view')
                ->name('index');

            Route::post('/', [ProductCategoryController::class, 'store'])
                ->middleware('permission:product_categories.create')
                ->name('store');

            Route::put('/{productCategory}', [ProductCategoryController::class, 'update'])
                ->middleware('permission:product_categories.update')
                ->name('update');

            Route::delete('/{productCategory}', [ProductCategoryController::class, 'destroy'])
                ->middleware('permission:product_categories.delete')
                ->name('destroy');

            Route::post('/{productCategory}/activate', [ProductCategoryController::class, 'activate'])
                ->middleware('permission:product_categories.update')
                ->name('activate');

            Route::post('/{productCategory}/deactivate', [ProductCategoryController::class, 'deactivate'])
                ->middleware('permission:product_categories.update')
                ->name('deactivate');
        });

    Route::prefix('products')
        ->name('products.')
        ->group(function () {
            Route::get('/', [ProductController::class, 'index'])
                ->middleware('permission:products.view')
                ->name('index');

            Route::get('/list', [ProductController::class, 'index'])
                ->middleware('permission:products.view')
                ->name('list');

            Route::get('/{product}', [ProductController::class, 'show'])
                ->middleware('permission:products.view')
                ->name('show');

            Route::get('/{product}/details', [ProductController::class, 'show'])
                ->middleware('permission:products.view')
                ->name('details');

            Route::get('/{product}/edit', [ProductController::class, 'edit'])
                ->middleware('permission:products.update')
                ->name('edit');

            Route::put('/{product}', [ProductController::class, 'update'])
                ->middleware('permission:products.update')
                ->name('update');

            Route::delete('/{product}', [ProductController::class, 'destroy'])
                ->middleware('permission:products.delete')
                ->name('destroy');

            Route::post('/{product}/deactivate', [ProductController::class, 'deactivate'])
                ->middleware('permission:products.update')
                ->name('deactivate');

            Route::post('/{product}/activate', [ProductController::class, 'activate'])
                ->middleware('permission:products.update')
                ->name('activate');
        });

    Route::prefix('stock')
        ->name('stock.')
        ->group(function () {
            Route::get('/', [StockController::class, 'index'])
                ->middleware('permission:stock.view')
                ->name('index');
        });

    Route::get('/files/{uuid}/thumbnail/{size?}', [FileProxyController::class, 'thumbnail'])
        ->where([
            'uuid' => '[A-Za-z0-9]+',
            'size' => 'sm|md|lg',
        ])
        ->name('files.thumbnail');

    Route::get('/files/{uuid}/view', [FileProxyController::class, 'view'])
        ->where('uuid', '[A-Za-z0-9]+')
        ->name('files.view');

    Route::prefix('team-structure')
        ->middleware('permission:team.structure.view')
        ->group(function () {
            Route::get('/', [TeamTreeController::class, 'index'])
                ->name('team.structure');

            Route::get('/root', [TeamTreeController::class, 'root'])
                ->name('team.structure.root');

            Route::get('/search', [TeamTreeController::class, 'search'])
                ->name('team.structure.search');

            Route::get('/nodes/{user}/children', [TeamTreeController::class, 'children'])
                ->name('team.structure.children');

            Route::get('/nodes/{user}/path', [TeamTreeController::class, 'path'])
                ->name('team.structure.path');
        });
});
