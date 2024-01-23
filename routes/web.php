<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'index']);
Route::post('/addToCart', [SiteController::class, 'addToCart'])->name('addToCart');
Route::post('/submitOrder', [SiteController::class, 'submitOrder'])->name('submitOrder');


Route::name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'getRegister'])->name('register');
    Route::get('/forgotPassword', [AuthController::class, 'getForgotPassword'])->name('forgotPassword');

    Route::name('post.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/forgotPassword', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Login with external providers 
    Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirect'])->name('redirect');
    Route::get('/auth/{provider}/callback', [AuthController::class, 'callback'])->name('callback');
});


Route::prefix('admin')->group(function () {
    Route::get('/', function() { return redirect(route('admin.dashboard')); });

    Route::name('admin.')->group(function () {
        Route::middleware(['auth', 'verified', 'admin', 'active'])->group(function() {
            Route::get('/setting/dbMigrate/{rollback?}', [SettingController::class, 'dbMigrate']);

            Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

            Route::resource('categories', CategoryController::class)->only([
                'index', 'store', 'update', 'destroy'
            ]);

            Route::resource('products', ProductController::class)->only([
                'index', 'store', 'update', 'destroy'
            ]);

            Route::prefix('users')->group(function () {
                Route::name('users.')->group(function () {
                    Route::get('/', [UserController::class, 'index'])->name('index');
                    Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
                    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
                    Route::put('/updateProfile', [UserController::class, 'updateProfile'])->name('updateProfile');
                    Route::get('/changePassword', [UserController::class, 'changePassword'])->name('changePassword');
                    Route::put('/updatePassword', [UserController::class, 'updatePassword'])->name('updatePassword');
                    Route::post('/impersonate', [UserController::class, 'impersonate'])->name('impersonate');
                });
            });

            Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications');
            Route::get('/transactions/{filter}', [TransactionController::class, 'index'])->name('transactions');
            Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        });
    });
});

Route::prefix('customer')->group(function () {
    Route::get('/', function() { return redirect(route('customer.dashboard')); });

    Route::name('customer.')->group(function () {
        Route::middleware(['auth', 'active'])->group(function() {
            Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])->name('dashboard');
            Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
            Route::put('/updateProfile', [CustomerDashboardController::class, 'updateProfile'])->name('updateProfile');
            Route::put('/updatePassword', [CustomerDashboardController::class, 'updatePassword'])->name('updatePassword');
            Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
            Route::get('/orders/{uid}', [CustomerDashboardController::class, 'showOrder'])->name('orders.show');
            Route::get('/transactions', [CustomerDashboardController::class, 'transactions'])->name('transactions');
            Route::get('/notifications', [CustomerDashboardController::class, 'notifications'])->name('notifications');
            Route::post('/notifications/markAllAsRead', [CustomerDashboardController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        });
    });
});
