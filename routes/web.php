<?php

use App\Http\Controllers\AssetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('signIn');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::resource('assets', AssetController::class);
    Route::get('/assets/{asset}/qr-download', [AssetController::class, 'downloadQr'])->name('assets.qr.download');
    Route::resource('users', EmployeeController::class);
    Route::resource('borrowings', BorrowingController::class);
    Route::get('/borrowings/{borrowings}/document', [BorrowingController::class, 'downloadDocument'])->name('borrowings.document');
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
});

