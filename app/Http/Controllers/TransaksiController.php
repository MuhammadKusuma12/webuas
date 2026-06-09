<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Item;
use App\Models\DetailTransaksi;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    /**
     * READ: Menampilkan semua daftar transaksi.
     */
    public function index()
    {
        $transaksi = Transaksi::all();

        return response()->json([
            'status' => 'sukses',
            'pesan'  => 'Berhasil mengambil daftar transaksi',
            'data'   => $transaksi
        ]);
    }

    public function create()
    {
        return response()->json(['pesan' => 'Endpoint untuk halaman form tambah transaksi']);
    }

    /**
     * CREATE: Menyimpan transaksi baru (Logika Kasir Final)
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang dikirim oleh kasir
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

            // 2. Cek stok dan hitung total belanjaan
            foreach ($request->items as $beli) {
                $item_db = Item::find($beli['item_id']);

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
                throw new \Exception("Uang pembayaran kurang! Total belanja: {$total_harga}");
            }

            // 3. Buat Nota Induk (Transaksi)
            $transaksi = Transaksi::create([
                'nomor_invoice' => 'INV-' . Carbon::now()->format('Ymd') . '-' . rand(1000, 9999),
                'user_id' => $request->user_id,
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

                // C. Catat Riwayat Mutasi Stok (Sudah diperbaiki batas hurufnya)
                MutasiStok::create([
                    'item_id' => $item->id,
                    'user_id' => $request->user_id,
                    'jenis_mutasi' => 'keluar',
                    'alasan_mutasi' => 'Penjualan', 
                    'jumlah' => $qty,
                    'stok_sebelum' => $stok_awal,
                    'stok_sesudah' => $item->stok,
                    'tanggal_mutasi' => Carbon::now(),
                    'keterangan' => "Terjual {$qty} ekor (Inv: {$transaksi->nomor_invoice})",
                ]);
            }

            // Kunci semua perubahan secara permanen
            DB::commit();

            return response()->json([
                'status' => 'sukses',
                'pesan' => 'Transaksi berhasil diproses',
                'data' => $transaksi
            ], 201);

        } catch (\Exception $e) {
            // Batalkan semua proses jika ada error di tengah jalan
            DB::rollBack();

            return response()->json([
                'status' => 'gagal',
                'pesan' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * READ: Menampilkan detail satu nota transaksi spesifik.
     */
    public function show(Transaksi $transaksi)
    {
        return response()->json([
            'status' => 'sukses',
            'data'   => $transaksi
        ]);
    }

    public function edit(Transaksi $transaksi)
    {
        return response()->json(['pesan' => 'Endpoint untuk halaman form edit transaksi']);
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        return response()->json(['pesan' => 'Logika update transaksi belum dirakit']);
    }

    /**
     * DELETE: Menghapus atau membatalkan transaksi.
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return response()->json([
            'status' => 'sukses',
            'pesan'  => 'Transaksi berhasil dibatalkan/dihapus'
        ]);
    }
}