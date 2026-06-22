<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\KodeItem;
use App\Models\DetailTransaksi;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Inertia\Inertia; 

class ItemController extends Controller
{
    /**
     * READ: Menampilkan semua daftar barang ke halaman Vue.
     */
    public function index()
    {
        $items = Item::with('kodeItem')->get();
        $kodeItems = KodeItem::all();

        return Inertia::render('Items/Index', [
            'items' => $items,
            'kodeItems' => $kodeItems,
        ]);
    }

    /**
     * CREATE: Menyimpan data barang baru (Dilengkapi Validasi Keamanan)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_item_id' => 'required|exists:kode_items,id',
            'nama_item' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        Item::create($validated);
        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    /**
     * UPDATE: Memperbarui data barang (Dilengkapi Validasi Keamanan)
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'kode_item_id' => 'required|exists:kode_items,id',
            'nama_item' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $item->update($validated);
        return redirect()->route('items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * DELETE: Menghapus data barang (Mencegah Kehancuran Relasi Data)
     */
    public function destroy(Item $item)
    {
        // Deteksi apakah barang sudah pernah ditransaksikan
        $pernahDijual = DetailTransaksi::where('item_id', $item->id)->exists();
        $punyaMutasi = MutasiStok::where('item_id', $item->id)->exists();

        if ($pernahDijual || $punyaMutasi) {
            return redirect()->back()->withErrors([
                'error' => 'Akses ditolak! Barang ini tidak bisa dihapus karena sudah memiliki riwayat penjualan atau mutasi stok.'
            ]);
        }

        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }

    // =========================================================================
    // FUNGSI KOSONG KkARENA MENGGUNAKAN SISTEM POP-UP (MODAL)
    // =========================================================================
    public function create() { return redirect()->route('items.index'); }
    public function edit(Item $item) { return redirect()->route('items.index'); }
    public function show(Item $item) { return redirect()->route('items.index'); }
}