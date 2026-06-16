<?php

namespace App\Http\Controllers;

use App\Models\KodeItem;
use Illuminate\Http\Request;
use Inertia\Inertia; // Wajib ditambahkan sebagai jembatan ke Vue

class KodeItemController extends Controller
{
    /**
     * READ: Menampilkan daftar Kategori/Kode Item ke halaman Vue
     */
    public function index()
    {
        // Mengambil semua data, diurutkan dari yang terbaru (latest)
        $kodeItems = KodeItem::latest()->get();

        // Mengirim data ke folder resources/js/Pages/KodeItems/Index.vue
        return Inertia::render('KodeItems/Index', [
            'kodeItems' => $kodeItems
        ]);
    }

    /**
     * CREATE: Menyimpan data kategori baru
     */
    public function store(Request $request)
    {
        KodeItem::create($request->all());

        return redirect()->route('kode-items.index');
    }

    /**
     * UPDATE: Memperbarui data kategori
     */
    public function update(Request $request, KodeItem $kodeItem)
    {
        $kodeItem->update($request->all());

        return redirect()->route('kode-items.index');
    }

    /**
     * DELETE: Menghapus data kategori
     */
    public function destroy(KodeItem $kodeItem)
    {
        $kodeItem->delete();

        return redirect()->route('kode-items.index');
    }

    // =========================================================================
    // FUNGSI DI BAWAH INI KOSONG KARENA ARIN MENGGUNAKAN SISTEM POP-UP (MODAL)
    // =========================================================================

    public function create()
    {
        return redirect()->route('kode-items.index');
    }

    public function edit(KodeItem $kodeItem)
    {
        return redirect()->route('kode-items.index');
    }

    public function show(KodeItem $kodeItem)
    {
        return redirect()->route('kode-items.index');
    }
}