<?php

namespace App\Http\Controllers;

use App\Models\MutasiStok;
use Inertia\Inertia;
use Illuminate\Http\Request;

class MutasiStokController extends Controller
{
    
    public function index()
    {
        
        $mutasiRaw = MutasiStok::with('item')->orderBy('id', 'desc')->get();


        $mutasiStok = $mutasiRaw->map(function ($mutasi) {
            return [
                'id'            => $mutasi->id,
                'kode_item'     => $mutasi->item ? $mutasi->item->kode_item : '-',
                'nama_item'     => $mutasi->item ? $mutasi->item->nama_item : 'Item Dihapus',
                'jenis_mutasi'  => $mutasi->jenis_mutasi,
                'jumlah'        => $mutasi->jumlah,
                'stok_sebelum'  => $mutasi->stok_sebelum,
                'stok_sesudah'  => $mutasi->stok_sesudah,
                'keterangan'    => $mutasi->keterangan ?? $mutasi->label, 
                'created_at'    => $mutasi->created_at ? $mutasi->created_at->format('Y-m-d') : '-',
            ];
        }); 

        
        return Inertia::render('MutasiStok/Index', [
            'mutasiStok' => $mutasiStok
        ]);
    }
}