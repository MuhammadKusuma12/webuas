<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\KodeItemController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MutasiStokController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('items', ItemController::class);
    Route::resource('transaksi', TransaksiController::class);
    Route::get('/mutasi-stok', [MutasiStokController::class, 'index']);
});

Route::resource('kode-items', KodeItemController::class);

require __DIR__.'/settings.php';