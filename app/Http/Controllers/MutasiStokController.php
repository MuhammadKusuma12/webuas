<?php

namespace App\Http\Controllers;

use App\Models\MutasiStok;
use Inertia\Inertia;
use Illuminate\Http\Request;

class MutasiStokController extends Controller
{
    /**
     * Menampilkan riwayat mutasi stok ke halaman Vue Arin.
     */
    public function index()
    {
        // 1. Ambil data mutasi stok sekaligus panggil relasi "item"-nya (Eager Loading)
        // diurutkan dari yang paling baru (terakhir dibuat)
        $mutasiRaw = MutasiStok::with('item')->orderBy('id', 'desc')->get();

        // 2. Transformasi data (Mapping) agar strukturnya pas dengan variabel di Vue Arin
        $mutasiStok = $mutasiRaw->map(function ($mutasi) {
            return [
                'id'            => $mutasi->id,
                'kode_item'     => $mutasi->item ? $mutasi->item->kode_item : '-',
                'nama_item'     => $mutasi->item ? $mutasi->item->nama_item : 'Item Dihapus',
                'jenis_mutasi'  => $mutasi->jenis_mutasi,
                'jumlah'        => $mutasi->jumlah,
                'stok_sebelum'  => $mutasi->stok_sebelum,
                'stok_sesudah'  => $mutasi->stok_sesudah,
                'keterangan'    => $mutasi->keterangan ?? $mutasi->label, // Pakai helper label dari model jika keterangan kosong
                'created_at'    => $mutasi->created_at ? $mutasi->created_at->format('Y-m-d') : '-',
            ];
        }); // <--- INI DIA PERBAIKANNYA: Ditambah kurung tutup biasa sebelum titik koma

        // 3. Kirim data yang sudah rapi ke ruang makan Vue
        return Inertia::render('MutasiStok/Index', [
            'mutasiStok' => $mutasiStok
        ]);
    }
}