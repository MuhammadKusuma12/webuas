<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia; // Wajib ditambahkan agar jembatan Inertia aktif

class ItemController extends Controller
{
    /**
     * READ: Menampilkan semua daftar barang ke halaman Vue.
     */
    public function index()
    {
        $items = Item::all();

        // Mencari file resources/js/Pages/Items/Index.vue milik Arin
        return Inertia::render('Items/Index', [
            'items' => $items
        ]);
    }

    /**
     * CREATE: Menyimpan data barang baru ke database.
     */
    public function store(Request $request)
    {
        Item::create($request->all());

        // Setelah berhasil menyimpan, kembalikan user ke halaman tabel
        return redirect()->route('items.index');
    }

    /**
     * UPDATE: Memperbarui data barang di database.
     */
    public function update(Request $request, Item $item)
    {
        $item->update($request->all());

        // Setelah berhasil update, kembalikan ke halaman tabel
        return redirect()->route('items.index');
    }

    /**
     * DELETE: Menghapus data barang dari database.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        // Setelah data dihapus, kembalikan ke halaman tabel
        return redirect()->route('items.index');
    }

    // =========================================================================
    // FUNGSI DI BAWAH INI KOSONG KARENA ARIN MENGGUNAKAN SISTEM POP-UP (MODAL)
    // =========================================================================

    public function create()
    {
        return redirect()->route('items.index');
    }

    public function edit(Item $item)
    {
        return redirect()->route('items.index');
    }

    public function show(Item $item)
    {
        return redirect()->route('items.index');
    }
}