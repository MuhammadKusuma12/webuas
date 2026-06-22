<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KodeItemController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MutasiStokController;

// ─── 1. ZONA PUBLIK ───────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── 2. AUTENTIKASI (hanya tamu) ─────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.store');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── 3. ZONA PEMBELI ──────────────────────────────────────────
Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::get('/checkout/{item}',                    [PembelianController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{item}',                   [PembelianController::class, 'store'])->name('checkout.store');
    Route::get('/pembayaran/{transaksi}',             [PembelianController::class, 'pembayaran'])->name('pembayaran.show');
    Route::post('/pembayaran/{transaksi}/konfirmasi', [PembelianController::class, 'konfirmasiPembayaran'])->name('pembayaran.konfirmasi');
    Route::get('/pesanan-saya',                       [PembelianController::class, 'riwayat'])->name('pesanan.index');
});

// ─── 3b. STRUK — bisa diakses pembeli, admin, dan pegawai ─────
Route::middleware('auth')->group(function () {
    Route::get('/struk/{transaksi}', [PembelianController::class, 'struk'])->name('struk.show');
});

// ─── 4. ZONA BACK-OFFICE (GABUNGAN ADMIN & PEGAWAI) ───────────
// Pegawai dan Admin sama-sama bisa masuk sini (Urusan Kasir & Verifikasi)
Route::middleware(['auth', 'role:admin,pegawai'])->group(function () {
    Route::get('/dashboard',     [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('transaksi', TransaksiController::class);
    
    // Logika Verifikasi E-Commerce
    Route::post('/transaksi/{transaksi}/terima', [TransaksiController::class, 'terima'])->name('transaksi.terima');
    Route::post('/transaksi/{transaksi}/tolak', [TransaksiController::class, 'tolak'])->name('transaksi.tolak');
});

// ─── 5. ZONA BOS (KHUSUS ADMIN) ───────────────────────────────
// HANYA Admin yang boleh masuk sini (Urusan Gudang, Barang, dan Laporan)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('items',     ItemController::class);
    Route::get('/mutasi-stok',   [MutasiStokController::class, 'index']);
    Route::resource('kode-items', KodeItemController::class);
});

require __DIR__.'/settings.php';