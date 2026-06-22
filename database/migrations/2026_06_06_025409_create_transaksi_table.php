<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_invoice', 50)->unique();

            $table->foreignId('user_id')->constrained('users');

            // --- KOLOM BARU UNTUK E-COMMERCE ---
            // Kita gunakan string tanpa batasan agar tidak ada lagi error 'truncated'
            $table->string('sumber')->default('offline'); // 'online' atau 'offline'
            $table->string('status')->default('selesai'); // 'menunggu_pembayaran', 'menunggu_konfirmasi', 'selesai', 'dibatalkan'
            $table->string('metode_bayar')->nullable();   // 'cash', 'transfer_bca', dll
            $table->integer('kode_unik')->default(0);     // 3 digit angka unik
            // -----------------------------------

            $table->decimal('total_harga', 15, 2);
            $table->decimal('bayar', 15, 2);
            $table->decimal('kembalian', 15, 2);

            $table->dateTime('tanggal_transaksi')->useCurrent();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};