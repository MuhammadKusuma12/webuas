<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BatalkanTransaksiBasi extends Command
{
    /**
     * Nama perintah yang akan kita ketik di terminal nanti
     */
    protected $signature = 'toko:batalkan-basi';

    /**
     * Deskripsi tugas si robot
     */
    protected $description = 'Membatalkan transaksi yang belum dibayar lebih dari 24 jam dan mengembalikan stok ikan';

    /**
     * Otak utama tempat logika eksekusi berjalan
     */
    public function handle()
    {
        // 1. Tentukan batas waktu (24 jam yang lalu dari detik ini)
        $batasWaktu = Carbon::now()->subHours(24);

        // 2. Cari semua transaksi yang nyangkut dan sudah basi
        $transaksiBasi = Transaksi::where('status', 'menunggu_pembayaran')
            ->where('tanggal_transaksi', '<', $batasWaktu)
            ->with('detailTransaksi')
            ->get();

        if ($transaksiBasi->isEmpty()) {
            $this->info('Aman! Tidak ada transaksi basi hari ini.');
            return;
        }

        // 3. Eksekusi pembatalan satu per satu dengan aman (Database Transaction)
        $jumlahDibatalkan = 0;

        foreach ($transaksiBasi as $trx) {
            try {
                DB::transaction(function () use ($trx) {
                    
                    // A. Kembalikan stok barang satu per satu
                    foreach ($trx->detailTransaksi as $detail) {
                        // Kunci baris item agar tidak tabrakan dengan pembeli yang sedang checkout
                        $item = Item::lockForUpdate()->find($detail->item_id);
                        
                        if ($item) {
                            $item->increment('stok', $detail->qty);
                        }
                    }

                    // B. Ubah status transaksi menjadi dibatalkan
                    $trx->update([
                        'status' => 'dibatalkan'
                    ]);
                });

                $jumlahDibatalkan++;

            } catch (\Exception $e) {
                $this->error("Gagal membatalkan transaksi ID {$trx->id}: " . $e->getMessage());
            }
        }

        $this->info("Berhasil! {$jumlahDibatalkan} transaksi basi telah dibatalkan dan stok dikembalikan.");
    }
}