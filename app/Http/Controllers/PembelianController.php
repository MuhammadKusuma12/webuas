<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaksi;
use App\Models\MutasiStok; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PembelianController extends Controller
{
    public function create(Item $item)
    {
        return Inertia::render('Pembelian', [
            'item' => $item,
        ]);
    }

    public function store(Request $request, Item $item)
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $transaksi = DB::transaction(function () use ($validated, $item) {

                $item = Item::lockForUpdate()->find($item->id);

                if ($validated['qty'] > $item->stok) {
                    throw new \Exception('Stok tidak mencukupi.');
                }

                $subtotal = $item->harga_jual * $validated['qty'];

                do {
                    $kodeUnik = rand(100, 999);
                    $sudahDipakai = Transaksi::where('kode_unik', $kodeUnik)
                        ->where('status', 'menunggu_pembayaran')
                        ->exists();
                } while ($sudahDipakai);

                $transaksi = Transaksi::create([
                    'nomor_invoice'     => 'INV-' . strtoupper(uniqid()),
                    'user_id'           => Auth::id(),
                    'sumber'            => 'online',
                    'status'            => 'menunggu_pembayaran',
                    'metode_bayar'      => 'transfer_bca',
                    'kode_unik'         => $kodeUnik,
                    'total_harga'       => $subtotal,
                    'bayar'             => 0,
                    'kembalian'         => 0,
                    'tanggal_transaksi' => now(),
                ]);

                $transaksi->detailTransaksi()->create([
                    'item_id'   => $item->id,
                    'nama_item' => $item->nama_item,
                    'harga'     => $item->harga_jual,
                    'qty'       => $validated['qty'],
                ]);

                
                $stok_awal = $item->stok;
                $item->decrement('stok', $validated['qty']);

                
                MutasiStok::create([
                    'item_id'        => $item->id,
                    'user_id'        => Auth::id(),
                    'jenis_mutasi'   => 'keluar',
                    'alasan_mutasi'  => 'Penjualan',
                    'jumlah'         => $validated['qty'],
                    'stok_sebelum'   => $stok_awal,
                    'stok_sesudah'   => $item->stok,
                    'tanggal_mutasi' => now(),
                    'keterangan'     => "Reservasi Online (Inv: {$transaksi->nomor_invoice})",
                ]);

                return $transaksi;
            });

            return redirect()->route('pembayaran.show', $transaksi->id)
                ->with('success', 'Pesanan dibuat! Silakan selesaikan pembayaran.');

        } catch (\Exception $e) {
            return back()->withErrors([
                'qty' => $e->getMessage(),
            ]);
        }
    }

    public function pembayaran(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== Auth::id()) { abort(403); }
        if ($transaksi->status !== 'menunggu_pembayaran') {
            return redirect()->route('pesanan.index');
        }

        $transaksi->load('detailTransaksi');

        return Inertia::render('Pembayaran', [
            'transaksi'       => $transaksi,
            'nominalTransfer' => $transaksi->total_harga + $transaksi->kode_unik,
            'rekeningTujuan'  => [
                'bank'      => 'BCA',
                'nomor'     => '1234567890',
                'atasnama'  => 'Toko Ikan Koi',
            ],
        ]);
    }

    public function konfirmasiPembayaran(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== Auth::id()) { abort(403); }
        if ($transaksi->status !== 'menunggu_pembayaran') {
            return back()->withErrors(['error' => 'Transaksi ini sudah diproses.']);
        }

        try {
            DB::transaction(function () use ($transaksi) {
                $transaksi->update([
                    'status' => 'menunggu_konfirmasi',
                    'bayar'  => $transaksi->total_harga + $transaksi->kode_unik,
                ]);
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('pesanan.index')
            ->with('success', 'Konfirmasi transfer berhasil! Pesanan sedang diverifikasi admin.');
    }

    public function struk(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== Auth::id() && !in_array(Auth::user()->role, ['admin', 'pegawai'])) {
            abort(403, 'Anda tidak berhak melihat struk ini.');
        }

        $transaksi->load('detailTransaksi', 'user');

        return Inertia::render('Struk', [
            'transaksi' => $transaksi,
        ]);
    }

    public function riwayat()
    {
        $transaksi = Transaksi::where('user_id', Auth::id())
            ->where('sumber', 'online')
            ->with('detailTransaksi')
            ->orderByDesc('id')
            ->get();

        return Inertia::render('PesananSaya', [
            'transaksi' => $transaksi,
        ]);
    }
}