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
            subtotal: number  // <-- ini yang ditambahin
        }>;
    };
}>();
const page = usePage();
const role = computed(() => (page.props.auth as any)?.user?.role);
</script>

<template>
    <Head title="Struk Pembelian" />

    <div class="min-h-screen flex items-center justify-center px-6 py-12"
        style="background: #f8f9ff; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">

        <div style="width: 100%; max-width: 420px;">

            <!-- Card struk -->
            <div style="background: #fff; border: 1px solid #bfc8c9; border-radius: 16px; overflow: hidden;">

                <!-- Header sukses -->
                <div style="background: linear-gradient(135deg, #004349 0%, #0d6b73 100%); padding: 32px; text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 8px;">✅</div>
                    <h1 style="font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 4px;">Pembelian Berhasil!</h1>
                    <p style="font-size: 0.8rem; color: #a8d8dc; font-family: monospace;">{{ transaksi.nomor_invoice }}</p>
                </div>

                <!-- Body struk -->
                <div style="padding: 24px;">

                    <!-- Info pembeli -->
                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #3f484a; margin-bottom: 16px;">
                        <span>Pembeli</span>
                        <span style="font-weight: 600; color: #0b1c30;">{{ transaksi.user?.nama || transaksi.user?.username }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #3f484a; margin-bottom: 16px;">
                        <span>Tanggal</span>
                        <span style="font-weight: 600; color: #0b1c30;">
                            {{ new Date(transaksi.tanggal_transaksi).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                        </span>
                    </div>

                    <!-- Garis putus -->
                    <div style="border-top: 2px dashed #e5eeff; margin: 16px 0;"></div>

                    <!-- Detail item -->
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px;">
                        <div
                            v-for="d in transaksi.detail_transaksi"
                            :key="d.id"
                            style="display: flex; justify-content: space-between; font-size: 0.875rem;"
                        >
                            <span style="color: #3f484a;">{{ d.nama_item }} <span style="color: #90a4b4;">x{{ d.qty }}</span></span>
                            <span style="font-weight: 600; color: #0b1c30;">Rp {{ Number(d.subtotal).toLocaleString('id-ID') }}</span>
                        </div>
                    </div>

                    <!-- Garis putus -->
                    <div style="border-top: 2px dashed #e5eeff; margin: 16px 0;"></div>

                    <!-- Total -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-size: 1rem; font-weight: 700; color: #0b1c30;">Total</span>
                        <span style="font-size: 1.2rem; font-weight: 800; color: #004349;">
                            Rp {{ Number(transaksi.total_harga).toLocaleString('id-ID') }}
                        </span>
                    </div>

                    <!-- Tombol cetak -->
                    <button
                        onclick="window.print()"
                        style="width: 100%; border: 1px solid #004349; color: #004349; text-align: center; padding: 12px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; background: transparent; cursor: pointer; margin-top: 8px;"
                    >
                        🖨️ Cetak Struk
                    </button>

                    <!-- Tombol aksi -->
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 12px;">
                        
                        <!-- Hanya untuk pembeli -->
                        <Link
                            v-if="role === 'pembeli'"
                            href="/pesanan-saya"
                            style="background: #004349; color: #fff; text-align: center; padding: 12px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; display: block; text-decoration: none;"
                            class="hover:opacity-90 transition-opacity"
                        >
                            Lihat Pesanan Saya
                        </Link>

                        <!-- Untuk pegawai/admin: balik ke transaksi -->
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
                            style="border: 1px solid #bfc8c9; color: #0b1c30; text-align: center; padding: 12px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; display: block; text-decoration: none;"
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