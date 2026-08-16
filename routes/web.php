<?php

use App\Http\Controllers\Pdf\InvoicePdfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\TrackingController;
use Illuminate\Support\Facades\Route;

// Public routes - Home & Shop
Route::get('/', [ShopController::class, 'home'])->name('home');

Route::prefix('shop')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('customer.shop.index');
    Route::get('/category/{slug}', [ShopController::class, 'category'])->name('customer.shop.category');
    Route::get('/{id}', [ShopController::class, 'show'])->name('customer.shop.show');
});

// Tracking route
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');

// Cart routes (public, no auth required for initial add)
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/count', [CartController::class, 'getCount'])->name('cart.count');
});

// Checkout routes
Route::prefix('checkout')->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/', [\App\Http\Controllers\Customer\CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/data', [\App\Http\Controllers\Customer\CheckoutController::class, 'getCheckoutData'])->name('checkout.data');
});

Route::prefix('pdf')->name('pdf.')->group(function () {
    Route::get('/invoice/{order}', [App\Http\Controllers\Pdf\InvoicePdfController::class, 'index'])->name('invoice');
});

// Auth required customer routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');

    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::post('/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/status/{status}', [AdminOrderController::class, 'getByStatus'])->name('getByStatus');
    });

    // CMS Routes
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/settings', [\App\Http\Controllers\Admin\CmsSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\CmsSettingController::class, 'store'])->name('settings.store');
        Route::put('/settings/{cmsSetting}', [\App\Http\Controllers\Admin\CmsSettingController::class, 'update'])->name('settings.update');

        Route::resource('carousels', \App\Http\Controllers\Admin\CmsCarouselController::class)->except(['create', 'show', 'edit']);
        Route::post('carousels/reorder', [\App\Http\Controllers\Admin\CmsCarouselController::class, 'reorder'])->name('carousels.reorder');
        Route::resource('social-media', \App\Http\Controllers\Admin\CmsSocialMediaController::class)->except(['create', 'show', 'edit']);
    });
});

require __DIR__ . '/auth.php';
