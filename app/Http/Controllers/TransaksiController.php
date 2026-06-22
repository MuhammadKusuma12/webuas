<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Item;
use App\Models\DetailTransaksi;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Inertia\Inertia;

class TransaksiController extends Controller
{
    /**
     * READ: Menampilkan daftar riwayat transaksi ke halaman Vue.
     */
    public function index()
    {
        $transaksi = Transaksi::with('detailTransaksi')->latest()->get();
        $items = Item::all();

        return Inertia::render('Transaksi/Index', [
            'transaksi' => $transaksi,
            'items' => $items,
        ]);
    }

    /**
     * CREATE: Menyimpan transaksi baru (Logika Kasir Final - Anti Race Condition)
     */
    public function store(Request $request)
    {
        // 1. Validasi data
        $request->validate([
            'user_id' => 'required|integer',
            'bayar' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        // Mulai "Ruang Kedap Udara" (Database Transaction)
        DB::beginTransaction();

        try {
            $total_harga = 0;
            $items_dibelis = [];

            // 2. Cek stok dan hitung total belanjaan (DENGAN LOCKING)
            foreach ($request->items as $beli) {
                // PERBAIKAN ISU #5: Mengunci baris item agar tidak direbut transaksi lain
                $item_db = Item::lockForUpdate()->find($beli['item_id']);

                if ($item_db->stok < $beli['qty']) {
                    throw new \Exception("Stok {$item_db->nama_item} tidak mencukupi! Sisa stok: {$item_db->stok}");
                }

                $subtotal = $item_db->harga_jual * $beli['qty'];
                $total_harga += $subtotal;

                // Simpan data sementara ke array
                $items_dibelis[] = [
                    'item' => $item_db,
                    'qty' => $beli['qty'],
                    'subtotal' => $subtotal
                ];
            }

            // Cek apakah uangnya cukup
            if ($request->bayar < $total_harga) {
                throw new \Exception("Uang pembayaran kurang! Total belanja: Rp" . number_format($total_harga, 0, ',', '.'));
            }

            // 3. Buat Nota Induk (Transaksi)
            $transaksi = Transaksi::create([
                'nomor_invoice' => 'INV-' . Carbon::now()->format('Ymd') . '-' . rand(1000, 9999),
                'user_id' => $request->user_id,
                'sumber' => 'offline', // PERBAIKAN ISU #4: Identifikasi transaksi kasir
                'status' => 'selesai', // Transaksi kasir langsung dianggap selesai
                'total_harga' => $total_harga,
                'bayar' => $request->bayar,
                'kembalian' => $request->bayar - $total_harga,
                'tanggal_transaksi' => Carbon::now(),
            ]);

            // 4. Proses Eksekusi Barang (Detail, Potong Stok, & Mutasi)
            foreach ($items_dibelis as $data) {
                $item = $data['item'];
                $qty = $data['qty'];
                $stok_awal = $item->stok;

                // A. Catat ke Detail Transaksi
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'item_id' => $item->id,
                    'nama_item' => $item->nama_item,
                    'harga' => $item->harga_jual,
                    'qty' => $qty,
                    'subtotal' => $data['subtotal'],
                ]);

                // B. Potong Stok Utama
                $item->stok = $stok_awal - $qty;
                $item->save();

                // C. Catat Riwayat Mutasi Stok
                MutasiStok::create([
                    'item_id' => $item->id,
                    'user_id' => $request->user_id,
                    'jenis_mutasi' => 'keluar',
                    'alasan_mutasi' => 'Penjualan', 
                    'jumlah' => $qty,
                    'stok_sebelum' => $stok_awal,
                    'stok_sesudah' => $item->stok,
                    'tanggal_mutasi' => Carbon::now(),
                    'keterangan' => "Terjual {$qty} ekor (Inv: {$transaksi->nomor_invoice}) via Kasir",
                ]);
            }

            // Kunci semua perubahan secara permanen
            DB::commit();

            return redirect()->route('transaksi.index');

        } catch (\Exception $e) {
            // Batalkan semua proses jika ada error
            DB::rollBack();

            return redirect()->back()->withErrors(['transaksi_error' => $e->getMessage()]);
        }
    }

    // =========================================================================
    // FUNGSI DI BAWAH INI DINONAKTIFKAN KARENA UI MENGGUNAKAN POP-UP (MODAL) VUE
    // =========================================================================

    public function show(Transaksi $transaksi)
    {
        return redirect()->route('transaksi.index');
    }

    public function create()
    {
        return redirect()->route('transaksi.index');
    }

    public function edit(Transaksi $transaksi)
    {
        return redirect()->route('transaksi.index');
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        return redirect()->route('transaksi.index');
    }

    /**
     * DELETE: Mencegah penghapusan untuk integritas laporan keuangan
     */
    public function destroy(Transaksi $transaksi)
    {
        // PERBAIKAN ISU #6: Menolak perintah hapus secara elegan
        return redirect()->back()->withErrors([
            'error' => 'Akses ditolak! Transaksi yang sudah tersimpan tidak dapat dihapus untuk menjaga integritas laporan mutasi dan keuangan.'
        ]);
    }

    /**
     * EKSEKUSI ADMIN: Menerima pesanan online
     */
    public function terima(Transaksi $transaksi)
    {
        if ($transaksi->status !== 'menunggu_konfirmasi') {
            return redirect()->back()->withErrors(['error' => 'Status transaksi tidak valid untuk diterima.']);
        }

        $transaksi->update(['status' => 'selesai']);

        return redirect()->back()->with('success', 'Pesanan online berhasil diverifikasi dan diselesaikan.');
    }

    /**
     * EKSEKUSI ADMIN: Menolak pesanan online (Stok wajib kembali)
     */
    public function tolak(Transaksi $transaksi)
    {
        if ($transaksi->status !== 'menunggu_konfirmasi') {
            return redirect()->back()->withErrors(['error' => 'Status transaksi tidak valid untuk ditolak.']);
        }

        DB::beginTransaction();

        try {
            // 1. Kembalikan stok item
            $transaksi->load('detailTransaksi');
            foreach ($transaksi->detailTransaksi as $detail) {
                $item = Item::lockForUpdate()->find($detail->item_id);
                if ($item) {
                    $item->increment('stok', $detail->qty);
                }
            }

            // 2. Ubah status jadi batal
            $transaksi->update(['status' => 'dibatalkan']);

            DB::commit();
            return redirect()->back()->with('success', 'Pesanan ditolak. Uang tidak valid dan stok telah dikembalikan ke etalase.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menolak pesanan: ' . $e->getMessage()]);
        }
    }
}