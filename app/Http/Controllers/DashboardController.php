<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaksi;
use App\Models\KodeItem;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil hanya transaksi yang benar-benar sah (sukses/selesai)
        $transaksiSukses = Transaksi::whereIn('status', ['selesai', 'menunggu_konfirmasi']);

        return Inertia::render('Dashboard', [
            'totalStok' => Item::sum('stok'),
            
            // Logika Presisi: Hanya menghitung jumlah struk yang sukses
            'totalTransaksi' => $transaksiSukses->count(),
            
            // Logika Tambahan: Menghitung total omzet (uang masuk)
            'totalPendapatan' => $transaksiSukses->sum('total_harga'),
            
            'totalJenis' => KodeItem::count(),
            
            // Logika Presisi: Hanya menampilkan 5 transaksi terakhir yang sukses di tabel dasbor
            'transaksiTerbaru' => $transaksiSukses->latest()->take(5)->get(),
        ]);
    }
}