<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';

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
    errors: any
}>();

function konfirmasi() {
    router.post(`/pembayaran/${props.transaksi.id}/konfirmasi`, {}, {
        onError: (err) => {
            console.error('Gagal konfirmasi pembayaran:', err);
            if (err.error) {
                alert('Gagal memproses: ' + err.error);
            }
        }
    });
}

function formatRupiah(num: number) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}
</script>

<template>
    <Head title="Instruksi Pembayaran" />

    <div class="flex flex-col gap-5 p-6 max-w-2xl mx-auto mt-10"
        style="font-family: 'Inter', sans-serif;">

        <div style="background: #fff; border: 1px solid #e5eeff; border-radius: 16px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

            <!-- Header -->
            <div style="text-align: center; margin-bottom: 24px;">
                <div style="width: 56px; height: 56px; background: #e5eeff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                    <span class="material-symbols-outlined" style="font-size: 28px; color: #004349; font-variation-settings: 'FILL' 1;">receipt_long</span>
                </div>
                <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.4rem; font-weight: 700; color: #0b1c30; margin-bottom: 4px;">
                    Selesaikan Pembayaran
                </h1>
                <p style="color: #3f484a; font-size: 0.875rem;">
                    Invoice: <span style="font-family: monospace; font-weight: 600; color: #004349;">{{ transaksi.nomor_invoice }}</span>
                </p>
            </div>

            <!-- Error -->
            <div v-if="errors && Object.keys(errors).length > 0"
                style="background: #ffdad6; color: #ba1a1a; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem; text-align: center; font-weight: 600; border: 1px solid #ffb4ab;">
                {{ errors.error || Object.values(errors)[0] }}
            </div>

            <!-- Info Banner -->
            <div style="background: #eff4ff; border: 1px solid #dce9ff; padding: 14px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 0.875rem; color: #004349; text-align: center; line-height: 1.6;">
                Pesanan Anda telah direservasi. Silakan transfer <strong>tepat</strong> sesuai nominal di bawah agar sistem dapat memverifikasi pembayaran.
            </div>

            <!-- Ringkasan Pesanan -->
            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 0.75rem; font-weight: 700; color: #3f484a; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.08em;">
                    Ringkasan Pesanan
                </h3>
                <div style="background: #f8f9ff; border: 1px solid #e5eeff; border-radius: 8px; padding: 12px; display: flex; flex-direction: column; gap: 8px;">
                    <div v-for="item in transaksi.detail_transaksi" :key="item.nama_item"
                        style="display: flex; justify-content: space-between; font-size: 0.875rem;">
                        <span style="color: #0b1c30;">
                            {{ item.nama_item }}
                            <span style="color: #6f797a; font-size: 0.8rem;"> ×{{ item.qty }}</span>
                        </span>
                        <span style="font-weight: 600; color: #0b1c30;">{{ formatRupiah(item.harga) }}</span>
                    </div>
                </div>
            </div>

            <!-- Rincian Biaya -->
            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 28px; font-size: 0.9rem;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e5eeff; padding-bottom: 10px;">
                    <span style="color: #3f484a;">Harga Barang</span>
                    <span style="font-weight: 600; color: #0b1c30;">{{ formatRupiah(transaksi.total_harga) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e5eeff; padding-bottom: 10px;">
                    <span style="color: #3f484a;">Kode Unik</span>
                    <span style="font-weight: 600; color: #934a29;">+ Rp {{ transaksi.kode_unik }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; background: #f8f9ff; border: 1px solid #e5eeff; padding: 14px; border-radius: 8px; align-items: center;">
                    <span style="color: #0b1c30; font-weight: 600;">Total Transfer</span>
                    <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: #004349; font-size: 1.4rem;">
                        {{ formatRupiah(nominalTransfer) }}
                    </span>
                </div>

                <!-- Rekening Tujuan -->
                <div style="padding: 16px; border: 1px solid #e5eeff; border-radius: 8px; background: #fff;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6f797a; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px;">
                        Transfer ke
                    </div>
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: #0b1c30; font-size: 1.1rem;">
                        {{ rekeningTujuan.bank }} – {{ rekeningTujuan.nomor }}
                    </div>
                    <div style="color: #3f484a; font-size: 0.875rem; margin-top: 2px;">
                        a.n. {{ rekeningTujuan.atasnama }}
                    </div>
                </div>
            </div>

            <!-- Tombol Konfirmasi -->
            <button
                @click="konfirmasi"
                class="hover:opacity-90 hover:shadow-md transition-all"
                style="width: 100%; background: #004349; color: #fff; padding: 14px; border-radius: 8px; font-size: 0.95rem; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;"
            >
                <span class="material-symbols-outlined" style="font-size: 20px; font-variation-settings: 'FILL' 1;">check_circle</span>
                Saya Sudah Transfer
            </button>

            <p style="text-align: center; font-size: 0.75rem; color: #6f797a; margin-top: 12px; line-height: 1.5;">
                Pesanan akan dibatalkan otomatis jika tidak ada pembayaran dalam 24 jam.
            </p>

        </div>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    font-family: 'Material Symbols Outlined';
}
</style>