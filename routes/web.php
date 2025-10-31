<?php

use App\Http\Controllers\TransactionManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProductManagementController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductTagController;
use App\Http\Controllers\BannerManagementController;
use App\Http\Controllers\VoucherManagementController;
use App\Http\Controllers\BrandManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqManagementController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\Utils\CkeditorController;

use App\Models\User;
use App\Models\Transaction;
use App\Notifications\Admin\PaymentNeedApprovalNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

use App\Http\Middleware\AdminAccessMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
})->name('welcome');

Route::prefix('admin')->middleware(['auth', 'view:admin,superadmin'])->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('admin.dashboard');

    // User Management
    Route::prefix('/user_management')->group(function () {
        Route::get('', [UserManagementController::class, 'index'])->name('admin.user-management');
        Route::get('create', [UserManagementController::class, 'create'])->name('admin.user-management.create');
        Route::get('profile/{id}', [UserManagementController::class, 'viewUserDetails'])->name('admin.user-management.view-user-details');
        Route::prefix('customer')->group(function () {
            Route::post('store', [UserManagementController::class, 'store'])->name('admin.customer-management.store');
            Route::delete('delete/{id}', [UserManagementController::class, 'destroy'])->name('admin.customer-management.destroy');
        });
        Route::prefix('admin')->middleware('view:superadmin')->group(function () {
            Route::put('promote/{id}', [UserManagementController::class, 'promoteRole'])->name('admin.admin-management.promote');
            Route::put('demote/{id}', [UserManagementController::class, 'demoteRole'])->name('admin.admin-management.demote');
            Route::delete('delete/{id}', [UserManagementController::class, 'destroy'])->name('admin.admin-management.destroy');
        });
    });

    // Product Management
    Route::prefix('product')->group(function () {
        Route::get('', [ProductManagementController::class, 'index'])->name('admin.product-management');

        Route::get('create', [ProductManagementController::class, 'create'])->name('admin.product-management.create');
        Route::post('store', [ProductManagementController::class, 'store'])->name('admin.product-management.store');

        Route::get('detail/{id}', [ProductManagementController::class, 'viewProductDetails'])->name('admin.product-management.view-product-details');
        Route::get('detail/statistics/{id}', [ProductManagementController::class, 'viewProductStatistics'])->name('admin.product-management.view-product-statistics');
        
        Route::put('update/{id}', [ProductManagementController::class, 'update'])->name('admin.product-management.update');
        Route::delete('delete/{id}', [ProductManagementController::class, 'destroy'])->name('admin.product-management.destroy');

        // Product Types
        Route::get('export-excel', [ProductTypeController::class, 'exportExcel'])->name('admin.product-management.export-excel');
        Route::prefix('detail/types')->group(function () {
            Route::get('{id}', [ProductTypeController::class, 'listProductTypes'])->name('admin.product-management.list-product-types');
            Route::post('store', [ProductTypeController::class, 'store'])->name('admin.product-management.store-product-type');
            Route::put('update/{id}', [ProductTypeController::class, 'update'])->name('admin.product-management.update-product-type');
            Route::delete('delete/{id}', [ProductTypeController::class, 'destroy'])->name('admin.product-management.delete-product-type');

            Route::delete('delete-media/{media_id}', [ProductTypeController::class, 'deleteMedia'])->name('admin.product-management.delete-media');
        });

        // Categories
        Route::prefix('categories')->group(function () {
            Route::get('', [ProductCategoryController::class, 'index'])->name('admin.product-category-management');
            Route::get('create', [ProductCategoryController::class, 'create'])->name('admin.product-category-management.create');
            Route::post('store', [ProductCategoryController::class, 'store'])->name('admin.product-category-management.store');

            Route::get('detail/{id}', [ProductCategoryController::class, 'viewCategoryDetails'])->name('admin.product-category-management.view-category-details');
            Route::put('update/{id}', [ProductCategoryController::class, 'update'])->name('admin.product-category-management.update');
            Route::delete('delete/{id}', [ProductCategoryController::class, 'destroy'])->name('admin.product-category-management.destroy');

            Route::get('sub/{parentId}', [ProductCategoryController::class, 'getSubCategories'])->name('admin.product-category-management.get-sub-categories');
        });

        // Tags
        Route::prefix('tags')->group(function () {
            Route::get('', [ProductTagController::class, 'index'])->name('admin.product-tag-management');
            Route::get('create', [ProductTagController::class, 'create'])->name('admin.product-tag-management.create');
            Route::get('detail/{id}', [ProductTagController::class, 'viewTagDetails'])->name('admin.product-tag-management.view-tag-form');
            Route::post('store', [ProductTagController::class, 'store'])->name('admin.product-tag-management.store');
            Route::put('update/{id}', [ProductTagController::class, 'update'])->name('admin.product-tag-management.update');
            Route::delete('delete/{id}', [ProductTagController::class, 'destroy'])->name('admin.product-tag-management.destroy');
        });
    });

    // Transaction Management
    Route::prefix('transaction')->group(function () {
        Route::get('', [TransactionManagementController::class, 'index'])->name('admin.transaction-management');
        Route::get('detail/{id}', [TransactionManagementController::class, 'viewTransactionDetails'])->name('admin.transaction-management.view-transaction-details');
        Route::get('detail/invoice/{id}', [TransactionManagementController::class, 'viewInvoice'])->name('admin.transaction-management.view-invoice');
        
        Route::put('accept-payment/{id}', [TransactionManagementController::class, 'acceptPayment'])->name('admin.transaction-management.accept-payment');
        Route::put('reject-payment/{id}', [TransactionManagementController::class, 'rejectPayment'])->name('admin.transaction-management.reject-payment');
        Route::put('cancel-transaction/{id}', [TransactionManagementController::class, 'cancelTransaction'])->name('admin.transaction-management.cancel-transaction');
        Route::put('confirm-delivery/{id}', [TransactionManagementController::class, 'confirmDelivery'])->name('admin.transaction-management.confirm-delivery');
        Route::put('deliver-transaction/{id}', [TransactionManagementController::class, 'confirmDelivered'])->name('admin.transaction-management.deliver-transaction');
   
        Route::get('export-excel', [TransactionManagementController::class, 'exportExcel'])->name('admin.transaction-management.export-excel');
    });

    Route::prefix('banner')->group(function () {
        Route::get('', [BannerManagementController::class, 'index'])->name('admin.banner-management');
        Route::get('create', [BannerManagementController::class, 'create'])->name('admin.banner-management.create');
        Route::post('store', [BannerManagementController::class, 'store'])->name('admin.banner-management.store');
        Route::get('detail/{id}', [BannerManagementController::class, 'viewBannerDetails'])->name('admin.banner-management.view-banner-details');
        Route::put('update/{id}', [BannerManagementController::class, 'update'])->name('admin.banner-management.update');
        Route::delete('delete/{id}', [BannerManagementController::class, 'destroy'])->name('admin.banner-management.destroy');
    });

    Route::prefix('voucher')->group(function () {
        Route::get('', [VoucherManagementController::class, 'index'])->name('admin.voucher-management');
        Route::get('create', [VoucherManagementController::class, 'create'])->name('admin.voucher-management.create');
        Route::post('store', [VoucherManagementController::class, 'store'])->name('admin.voucher-management.store');
        Route::get('detail/{id}', [VoucherManagementController::class, 'viewVoucherDetails'])->name('admin.voucher-management.view-voucher-details');
        Route::put('update/{id}', [VoucherManagementController::class, 'update'])->name('admin.voucher-management.update');
        Route::delete('delete/{id}', [VoucherManagementController::class, 'destroy'])->name('admin.voucher-management.destroy');
    });

    Route::prefix('brand')->group(function () {
        Route::get('', [BrandManagementController::class, 'index'])->name('admin.brand-management');
        Route::get('create', [BrandManagementController::class, 'create'])->name('admin.brand-management.create');
        Route::get('detail/{id}', [BrandManagementController::class, 'viewBrandDetails'])->name('admin.brand-management.view-brand-details');
        Route::post('store', [BrandManagementController::class, 'store'])->name('admin.brand-management.store');
        Route::put('update/{id}', [BrandManagementController::class, 'update'])->name('admin.brand-management.update');
        Route::delete('delete/{id}', [BrandManagementController::class, 'destroy'])->name('admin.brand-management.destroy');
    });

    Route::prefix('faq')->group(function () {
        Route::get('', [FaqManagementController::class, 'index'])->name('admin.faq-management');
        Route::get('create', [FaqManagementController::class, 'create'])->name('admin.faq-management.create');
        Route::get('detail/{id}', [FaqManagementController::class, 'viewFaqDetails'])->name('admin.faq-management.view-faq-details');
        Route::post('store', [FaqManagementController::class, 'store'])->name('admin.faq-management.store');
        Route::put('update/{id}', [FaqManagementController::class, 'update'])->name('admin.faq-management.update');
        Route::delete('delete/{id}', [FaqManagementController::class, 'destroy'])->name('admin.faq-management.destroy');
    });

    Route::prefix('notification')->group(function () {
        Route::get('', [NotificationController::class, 'index'])->name('admin.notification');
        Route::get('mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('admin.notification.mark-all-as-read');
        Route::get('mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('admin.notification.mark-as-read');
        Route::get('mark-as-unread/{id}', [NotificationController::class, 'markAsUnread'])->name('admin.notification.mark-as-unread');
        Route::get('/notifications/{id}/action', [NotificationController::class, 'handleNotificationAction'])->name('notifications.action');
    });
});

Route::get('/403', function () {
    return response()->view('errors.403', [], 403);
})->name('error.403');

Route::post('/ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
