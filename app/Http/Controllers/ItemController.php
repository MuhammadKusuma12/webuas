<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * READ: Menampilkan semua daftar barang.
     */
    public function index()
    {
        // Mengambil semua data dari tabel items
        $items = Item::all();

        return response()->json([
            'status' => 'sukses',
            'pesan'  => 'Berhasil mengambil daftar barang',
            'data'   => $items
        ]);
    }

    /**
     * Menampilkan form UI untuk tambah data.
     * (Kita kembalikan pesan JSON dulu karena UI belum ada)
     */
    public function create()
    {
        return response()->json(['pesan' => 'Endpoint ini khusus memanggil halaman form UI tambah data']);
    }

    /**
     * CREATE: Menyimpan data barang baru ke database.
     */
    public function store(Request $request)
    {
        // Catatan: Pastikan validasi disesuaikan dengan nama kolom di database kamu
        $request->validate([
            // Contoh validasi dasar:
            // 'nama_item' => 'required|string',
            // 'harga' => 'required|numeric',
        ]);

        $item = Item::create($request->all());

        return response()->json([
            'status' => 'sukses',
            'pesan'  => 'Barang baru berhasil ditambahkan',
            'data'   => $item
        ], 201); // Status 201 = Created
    }

    /**
     * READ: Menampilkan detail satu barang spesifik.
     */
    public function show(Item $item)
    {
        return response()->json([
            'status' => 'sukses',
            'data'   => $item
        ]);
    }

    /**
     * Menampilkan form UI untuk edit data.
     */
    public function edit(Item $item)
    {
        return response()->json(['pesan' => 'Endpoint ini khusus memanggil halaman form UI edit data']);
    }

    /**
     * UPDATE: Memperbarui data barang di database.
     */
    public function update(Request $request, Item $item)
    {
        $item->update($request->all());

        return response()->json([
            'status' => 'sukses',
            'pesan'  => 'Data barang berhasil diperbarui',
            'data'   => $item
        ]);
    }

    /**
     * DELETE: Menghapus data barang dari database.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json([
            'status' => 'sukses',
            'pesan'  => 'Barang berhasil dihapus'
        ]);
    }
}