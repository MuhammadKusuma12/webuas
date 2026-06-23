<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps<{
    transaksi: {
        id: number
        nomor_invoice: string
        total_harga: number
        bayar: number
        kembalian: number
        tanggal_transaksi: string
        status: string
        user: {
            id: number
            nama: string
            username: string
        }
        detail_transaksi: Array<{
            id: number
            nama_item: string
            harga: number
            qty: number
            subtotal: number
        }>;
    };
}>();

const page = usePage();
const role = computed(() => (page.props.auth as any)?.user?.role);

function formatRupiah(num: number) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}
</script>

<template>
    <Head title="Struk Pembelian" />

    <div class="min-h-screen flex items-center justify-center px-6 py-12"
        style="background: #f8f9ff; font-family: 'Inter', sans-serif;">

        <div style="width: 100%; max-width: 420px;">

            <!-- Card struk -->
            <div style="background: #fff; border: 1px solid #e5eeff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

                <!-- Header sukses -->
                <div style="background: linear-gradient(135deg, #004349 0%, #0d6b73 100%); padding: 32px; text-align: center;">
                    <div style="width: 52px; height: 52px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                        <span class="material-symbols-outlined" style="font-size: 28px; color: #fff; font-variation-settings: 'FILL' 1;">check_circle</span>
                    </div>
                    <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.2rem; font-weight: 700; color: #fff; margin-bottom: 6px;">
                        Pembelian Berhasil!
                    </h1>
                    <p style="font-size: 0.8rem; color: #abeef6; font-family: monospace;">{{ transaksi.nomor_invoice }}</p>
                </div>

                <!-- Body struk -->
                <div style="padding: 24px;">

                    <!-- Info pembeli -->
                    <div style="display: flex; justify-content: space-between; font-size: 0.875rem; color: #3f484a; margin-bottom: 10px;">
                        <span>Pembeli</span>
                        <span style="font-weight: 600; color: #0b1c30;">{{ transaksi.user?.nama || transaksi.user?.username }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.875rem; color: #3f484a; margin-bottom: 16px;">
                        <span>Tanggal</span>
                        <span style="font-weight: 600; color: #0b1c30;">
                            {{ new Date(transaksi.tanggal_transaksi).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                        </span>
                    </div>

                    <div style="border-top: 2px dashed #e5eeff; margin: 16px 0;"></div>

                    <!-- Detail item -->
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px;">
                        <div
                            v-for="d in transaksi.detail_transaksi"
                            :key="d.id"
                            style="display: flex; justify-content: space-between; font-size: 0.875rem;"
                        >
                            <span style="color: #3f484a;">
                                {{ d.nama_item }}
                                <span style="color: #6f797a; font-size: 0.8rem;"> ×{{ d.qty }}</span>
                            </span>
                            <span style="font-weight: 600; color: #0b1c30;">{{ formatRupiah(d.subtotal) }}</span>
                        </div>
                    </div>

                    <div style="border-top: 2px dashed #e5eeff; margin: 16px 0;"></div>

                    <!-- Total -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 700; color: #0b1c30;">Total</span>
                        <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.25rem; font-weight: 800; color: #004349;">
                            {{ formatRupiah(transaksi.total_harga) }}
                        </span>
                    </div>

                    <!-- Tombol cetak -->
                    <button
                        onclick="window.print()"
                        style="width: 100%; border: 1px solid #004349; color: #004349; text-align: center; padding: 11px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;"
                        class="hover:bg-[#e5eeff] transition-colors"
                    >
                        <span class="material-symbols-outlined" style="font-size: 18px;">print</span>
                        Cetak Struk
                    </button>

                    <!-- Tombol aksi -->
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 10px;">

                        <Link
                            v-if="role === 'pembeli'"
                            href="/pesanan-saya"
                            style="background: #004349; color: #fff; text-align: center; padding: 12px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; display: block; text-decoration: none;"
                            class="hover:opacity-90 transition-opacity"
                        >
                            Lihat Pesanan Saya
                        </Link>

                        <Link
                            v-else-if="role === 'pegawai' || role === 'admin'"
                            href="/transaksi"
                            style="background: #004349; color: #fff; text-align: center; padding: 12px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; display: block; text-decoration: none;"
                            class="hover:opacity-90 transition-opacity"
                        >
                            Kembali ke Transaksi
                        </Link>

                        <Link
                            href="/"
                            style="border: 1px solid #e5eeff; color: #0b1c30; text-align: center; padding: 12px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; display: block; text-decoration: none;"
                            class="hover:bg-[#f8f9ff] transition-colors"
                        >
                            Kembali ke Katalog
                        </Link>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    font-family: 'Material Symbols Outlined';
}
</style>