<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

// Route Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

// Route Auth
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dasbor Utama
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Utama
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::patch('/purchase-orders/{id}/status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase-orders.update-status');
    Route::resource('users', UserController::class);

    // Alias Route Bahasa Indonesia
    Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProductController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{id}', [ProductController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('produk.destroy');

    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    Route::get('/pesanan', [PurchaseOrderController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/create', [PurchaseOrderController::class, 'create'])->name('pesanan.create');
    Route::post('/pesanan', [PurchaseOrderController::class, 'store'])->name('pesanan.store');
    Route::get('/pesanan/{id}', [PurchaseOrderController::class, 'show'])->name('pesanan.show');
    Route::patch('/pesanan/{id}/status', [PurchaseOrderController::class, 'updateStatus'])->name('pesanan.update-status');
    Route::delete('/pesanan/{id}', [PurchaseOrderController::class, 'destroy'])->name('pesanan.destroy');
    Route::get('/po', [PurchaseOrderController::class, 'index'])->name('po.index');

    // Modul Laporan
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
});