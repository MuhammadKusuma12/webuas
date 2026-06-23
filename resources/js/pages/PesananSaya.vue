<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    transaksi: Array<{
        id: number;
        nomor_invoice: string;
        total_harga: number;
        tanggal_transaksi: string;
        status: string;
        detail_transaksi: Array<{
            id: number;
            nama_item: string;
            harga: number;
            qty: number;
        }>;
    }>;
}>();

function formatRupiah(num: number) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function formatTanggal(dateStr: string) {
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
}
</script>

<template>
    <Head title="Pesanan Saya" />

    <div class="min-h-screen px-6 py-10" style="background: #f8f9ff; font-family: 'Inter', sans-serif;">
        <div class="max-w-2xl mx-auto">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0b1c30;">
                    Pesanan Saya
                </h1>
                <Link href="/"
                    style="font-size: 0.875rem; color: #3f484a; text-decoration: none; display: flex; align-items: center; gap: 4px;"
                    class="hover:opacity-70 transition-opacity">
                    ← Kembali ke Katalog
                </Link>
            </div>

            <!-- Kosong -->
            <div v-if="transaksi.length === 0"
                class="text-center py-16"
                style="color: #6f797a; font-size: 0.95rem;">
                <div style="font-size: 3rem; margin-bottom: 12px;">🛒</div>
                Belum ada pesanan.
            </div>

            <!-- List transaksi -->
            <div
                v-for="trx in transaksi"
                :key="trx.id"
                style="background: #fff; border: 1px solid #e5eeff; border-radius: 12px; padding: 16px 20px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.04);"
                class="hover:shadow-md transition-shadow"
            >
                <!-- Kiri: invoice + tanggal -->
                <div>
                    <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 0.9rem; color: #0b1c30; margin-bottom: 3px;">
                        {{ trx.nomor_invoice }}
                    </p>
                    <p style="font-size: 0.75rem; color: #6f797a;">
                        {{ formatTanggal(trx.tanggal_transaksi) }}
                    </p>
                </div>

                <!-- Tengah: harga + status -->
                <div style="text-align: right;">
                    <p style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 0.95rem; color: #004349; margin-bottom: 4px;">
                        {{ formatRupiah(trx.total_harga) }}
                    </p>
                    <span style="font-size: 0.7rem; font-weight: 600; background: #e5eeff; color: #004349; padding: 2px 10px; border-radius: 999px;">
                        {{ trx.status }}
                    </span>
                </div>

                <!-- Kanan: link struk -->
                <Link
                    :href="`/struk/${trx.id}`"
                    style="font-size: 0.875rem; font-weight: 600; color: #004349; text-decoration: underline; white-space: nowrap; margin-left: 16px;"
                    class="hover:opacity-70 transition-opacity"
                >
                    Lihat Struk
                </Link>
            </div>

        </div>
    </div>
</template>