<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';

// 1. Strict Typing: Mengunci struktur data agar tidak terjadi salah panggil variabel
const props = defineProps<{
    transaksi: {
        id: number
        nomor_invoice: string
        total_harga: number
        kode_unik: number
        status: string
        detail_transaksi: Array<{
            nama_item: string
            harga: number
            qty: number
        }>
    }
    nominalTransfer: number       
    rekeningTujuan: {
        bank: string
        nomor: string
        atasnama: string
    }
    errors: any // Penangkap error gaib dari Laravel
}>();

// 2. Fungsi eksekusi yang dilengkapi radar pelacak
function konfirmasi() {
    router.post(`/pembayaran/${props.transaksi.id}/konfirmasi`, {}, {
        onError: (err) => {
            console.error("Server menolak dengan alasan:", err);
            // Menampilkan peringatan ke layar jika terjadi penolakan
            if (err.error) {
                alert('Gagal memproses: ' + err.error);
            }
        }
    });
}
</script>

<template>
    <Head title="Instruksi Pembayaran" />
    
    <div class="flex flex-col gap-5 p-6 max-w-2xl mx-auto mt-10" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">
        
        <div style="background: #fff; border: 1px solid #bfc8c9; border-radius: 12px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            <div style="text-align: center; margin-bottom: 24px;">
                <div class="text-4xl mb-3">🧾</div>
                <h1 style="font-size: 1.5rem; font-weight: 800; color: #0b1c30;">Selesaikan Pembayaran</h1>
                <p style="color: #3f484a; font-size: 0.875rem;">Invoice: <span style="font-family: monospace; font-weight: 600;">{{ transaksi.nomor_invoice }}</span></p>
            </div>

            <div v-if="errors && Object.keys(errors).length > 0" style="background: #ffebee; color: #ba1a1a; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem; text-align: center; font-weight: 600; border: 1px solid #ffcdd2;">
                ⚠️ {{ errors.error || Object.values(errors)[0] }}
            </div>

            <div style="background: #eff4ff; border: 1px solid #dce9ff; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-size: 0.875rem; color: #004349; text-align: center;">
                Pesanan Anda telah direservasi! Silakan transfer <strong>TEPAT</strong> sesuai nominal di bawah ini hingga 3 digit terakhir agar sistem dapat memverifikasi.
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #3f484a; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.05em;">Ringkasan Pesanan</h3>
                <div style="background: #f8f9ff; border-radius: 8px; padding: 12px; display: flex; flex-direction: column; gap: 8px;">
                    <div v-for="item in transaksi.detail_transaksi" :key="item.nama_item" style="display: flex; justify-content: space-between; font-size: 0.875rem;">
                        <span style="color: #0b1c30;">{{ item.nama_item }} <span style="color: #90a4b4;">x{{ item.qty }}</span></span>
                        <span style="font-weight: 600; color: #0b1c30;">Rp {{ item.harga.toLocaleString('id-ID') }}</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 32px; font-size: 0.95rem;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #bfc8c9; padding-bottom: 10px;">
                    <span style="color: #3f484a;">Harga Barang</span>
                    <span style="font-weight: 600; color: #0b1c30;">Rp {{ transaksi.total_harga.toLocaleString('id-ID') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #bfc8c9; padding-bottom: 10px;">
                    <span style="color: #3f484a;">Kode Unik Transaksi</span>
                    <span style="font-weight: 600; color: #ba1a1a;">+ Rp {{ transaksi.kode_unik }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; background: #f8f9ff; padding: 12px; border-radius: 8px; align-items: center;">
                    <span style="color: #0b1c30; font-weight: 600;">Total Transfer</span>
                    <span style="font-weight: 800; color: #004349; font-size: 1.5rem;">Rp {{ nominalTransfer.toLocaleString('id-ID') }}</span>
                </div>
                
                <div style="margin-top: 10px; padding: 16px; border: 1px solid #bfc8c9; border-radius: 8px;">
                    <div style="font-size: 0.8rem; color: #90a4b4; text-transform: uppercase; margin-bottom: 4px;">Transfer Ke:</div>
                    <div style="font-weight: 800; color: #0b1c30; font-size: 1.2rem;">{{ rekeningTujuan.bank }} {{ rekeningTujuan.nomor }}</div>
                    <div style="color: #3f484a; font-size: 0.9rem;">a.n {{ rekeningTujuan.atasnama }}</div>
                </div>
            </div>

            <button
                @click="konfirmasi"
                style="width: 100%; background: #004349; color: #fff; padding: 14px; border-radius: 8px; font-size: 1rem; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s;"
                class="hover:opacity-90 hover:shadow-lg"
            >
                ✅ Saya Sudah Transfer
            </button>
            <p style="text-align: center; font-size: 0.75rem; color: #90a4b4; margin-top: 12px;">
                Jika Anda tidak melakukan transfer dalam 24 jam, pesanan akan dibatalkan otomatis oleh sistem.
            </p>
        </div>

    </div>
</template>