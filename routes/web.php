<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect Root to appropriate page
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'owner' => redirect()->route('owner.dashboard'),
            'gudang' => redirect()->route('gudang.dashboard'),
            'kasir' => redirect()->route('kasir.pos'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Owner / Manajer Routes (Strictly role:owner)
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/transactions', [OwnerController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/{id}', [OwnerController::class, 'showTransaction'])->name('transactions.show');
    Route::get('/export-csv', [OwnerController::class, 'exportCsv'])->name('export_csv');
    Route::get('/print-report', [OwnerController::class, 'printReport'])->name('print_report');
    
    // Purchases (Monitoring Barang Masuk by Owner)
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/{id}', [PurchaseController::class, 'show'])->name('purchases.show');
    
    // Employee Account Management (CRUD)
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
});

// Admin Gudang Routes (Strictly role:gudang)
Route::middleware(['auth', 'role:gudang'])->prefix('gudang')->name('gudang.')->group(function () {
    Route::get('/dashboard', [GudangController::class, 'dashboard'])->name('dashboard');
    
    // Master Categories CRUD
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
    
    // Master Products CRUD
    Route::resource('products', ProductController::class)->except(['create', 'show', 'edit']);
    
    // Multi-Item Purchases / Transaksi Barang Masuk
    Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store', 'show']);
    
    // Legacy single-item Restock Log
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
});

// Goods Receipt Note Print View (Accessible by Gudang & Owner)
Route::middleware(['auth'])->get('/purchases/{id}/print', [PurchaseController::class, 'printNote'])->name('gudang.purchases.print');

// Kasir (Front-Office) Routes (Strictly role:kasir)
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/pos', [KasirController::class, 'pos'])->name('pos');
    Route::get('/api/products', [KasirController::class, 'apiProducts'])->name('api.products');
    Route::post('/checkout', [KasirController::class, 'checkout'])->name('checkout');
    Route::get('/transactions', [KasirController::class, 'transactions'])->name('transactions');
});

// Thermal Receipt View (Accessible by Kasir who made it, and Owner)
Route::middleware(['auth'])->get('/receipt/{id}', [KasirController::class, 'receipt'])->name('kasir.receipt');
