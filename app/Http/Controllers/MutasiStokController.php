<?php

namespace App\Http\Controllers;

use App\Models\MutasiStok;
use Illuminate\Http\Request;

class MutasiStokController extends Controller
{
    /**
     * READ: Menampilkan buku laporan riwayat keluar-masuk barang
     */
    public function index()
    {
        $laporan = MutasiStok::with(['item', 'user'])
                    ->latest('tanggal_mutasi')
                    ->get();

        return response()->json([
            'status' => 'sukses',
            'pesan'  => 'Berhasil mengambil Laporan Mutasi Stok',
            'data'   => $laporan
        ]);
    }
}