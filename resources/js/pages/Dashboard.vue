<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard } from '@/routes';

defineProps<{
    totalStok: number;
    totalTransaksi: number;
    totalJenis: number;
    totalPendapatan: number;
    pendapatanHariIni: number;
    pendapatanBulanIni: number;
    transaksiTerbaru: Array<{
        id: number;
        nomor_invoice: string;
        total_harga: number;
        created_at: string;
    }>;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

function formatDate(dateStr: string) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

function formatRupiah(num: number) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}
</script>

<template>
    <Head title="Dashboard - Koi Admin" />

    <div class="flex flex-col gap-8 p-6 min-h-full bg-[#f8f9ff]">

        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <h2 class="text-[32px] font-semibold leading-10 text-[#0b1c30]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Ringkasan Toko
                </h2>
                <p class="text-base text-[#3f484a] mt-1" style="font-family: 'Inter', sans-serif;">
                    Selamat datang, {{ $page.props.auth.user.nama || 'Administrator' }}. Berikut ringkasan toko hari ini.
                </p>
            </div>
            <div class="flex gap-3">
                <Link href="/" class="flex items-center gap-2 px-4 py-2 bg-white border border-[#bfc8c9] rounded-lg text-sm font-semibold text-[#004349] hover:bg-[#e5eeff] transition-colors shadow-sm">
                    <span class="material-symbols-outlined" style="font-size:18px;">storefront</span>
                    Lihat Toko
                </Link>
                <button class="flex items-center gap-2 px-4 py-2 bg-white border border-[#bfc8c9] rounded-lg text-sm font-medium text-[#0b1c30] hover:bg-[#e5eeff] transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[#004349]" style="font-size:18px;">calendar_today</span>
                    {{ new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                </button>
                <Link
                    v-if="$page.props.auth.user.role === 'admin'"
                    href="/items/create"
                    class="flex items-center gap-2 px-5 py-2 bg-[#004349] text-white rounded-lg text-sm font-semibold hover:bg-[#0d5c63] active:scale-95 transition-all shadow-sm"
                >
                    <span class="material-symbols-outlined" style="font-size:18px;">add</span>
                    Tambah Item
                </Link>
            </div>
        </header>

        <!-- Stat Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

            <!-- Total Stok -->
            <div class="zen-stat-card bg-white rounded-xl p-5 shadow-sm border border-[#e5eeff] border-l-4 border-l-[#004349] flex flex-col justify-between h-40 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="bg-[#abeef6] p-2.5 rounded-lg">
                        <span class="material-symbols-outlined text-[#004349]" style="font-variation-settings: 'FILL' 1; font-size:20px;">inventory_2</span>
                    </div>
                    <span class="text-[10px] font-semibold text-[#004f55] bg-[#e5eeff] px-2 py-0.5 rounded-full tracking-wide">Stok aktif</span>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-[#3f484a]">Total Stok Item</p>
                    <p class="text-4xl font-bold text-[#004349] mt-1 leading-none">{{ totalStok }}</p>
                </div>
            </div>

            <!-- Total Transaksi -->
            <div class="zen-stat-card bg-white rounded-xl p-5 shadow-sm border border-[#e5eeff] border-l-4 border-l-[#934a29] flex flex-col justify-between h-40 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="bg-[#ffdbce] p-2.5 rounded-lg">
                        <span class="material-symbols-outlined text-[#934a29]" style="font-variation-settings: 'FILL' 1; font-size:20px;">payments</span>
                    </div>
                    <span class="text-[10px] font-semibold text-[#934a29] bg-[#ffdbce] px-2 py-0.5 rounded-full tracking-wide">Selesai</span>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-[#3f484a]">Total Transaksi</p>
                    <p class="text-4xl font-bold text-[#934a29] mt-1 leading-none">{{ totalTransaksi }}</p>
                </div>
            </div>

            <!-- Total Jenis -->
            <div class="zen-stat-card bg-white rounded-xl p-5 shadow-sm border border-[#e5eeff] border-l-4 border-l-[#004443] flex flex-col justify-between h-40 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="bg-[#98f2f0] p-2.5 rounded-lg">
                        <span class="material-symbols-outlined text-[#004443]" style="font-variation-settings: 'FILL' 1; font-size:20px;">category</span>
                    </div>
                    <span class="text-[10px] font-semibold text-[#004443] bg-[#98f2f0] px-2 py-0.5 rounded-full tracking-wide">Kategori</span>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-[#3f484a]">Total Jenis Item</p>
                    <p class="text-4xl font-bold text-[#004443] mt-1 leading-none">{{ totalJenis }}</p>
                </div>
            </div>

            <!-- Pendapatan Hari Ini -->
            <div class="zen-stat-card bg-white rounded-xl p-5 shadow-sm border border-[#e5eeff] border-l-4 border-l-[#004349] flex flex-col justify-between h-40 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="bg-[#abeef6] p-2.5 rounded-lg">
                        <span class="material-symbols-outlined text-[#004349]" style="font-variation-settings: 'FILL' 1; font-size:20px;">today</span>
                    </div>
                    <span class="text-[10px] font-semibold text-[#004f55] bg-[#e5eeff] px-2 py-0.5 rounded-full tracking-wide">Hari ini</span>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-[#3f484a]">Pendapatan Hari Ini</p>
                    <p class="text-xl font-bold text-[#004349] mt-1 leading-tight">
                        {{ formatRupiah(pendapatanHariIni) }}
                    </p>
                </div>
            </div>

            <!-- Pendapatan Bulan Ini -->
            <div class="zen-stat-card bg-white rounded-xl p-5 shadow-sm border border-[#e5eeff] border-l-4 border-l-[#934a29] flex flex-col justify-between h-40 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="bg-[#ffdbce] p-2.5 rounded-lg">
                        <span class="material-symbols-outlined text-[#934a29]" style="font-variation-settings: 'FILL' 1; font-size:20px;">calendar_month</span>
                    </div>
                    <span class="text-[10px] font-semibold text-[#934a29] bg-[#ffdbce] px-2 py-0.5 rounded-full tracking-wide">Bulan ini</span>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-[#3f484a]">Pendapatan Bulan Ini</p>
                    <p class="text-xl font-bold text-[#934a29] mt-1 leading-tight">
                        {{ formatRupiah(pendapatanBulanIni) }}
                    </p>
                </div>
            </div>

        </section>

        <!-- Tabel + Sidebar -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Tabel Transaksi -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-[#e5eeff] shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-[#e5eeff]">
                    <h3 class="text-lg font-semibold text-[#0b1c30]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Transaksi Terbaru
                    </h3>
                    <Link href="/transaksi" class="text-sm font-medium text-[#004349] hover:underline">
                        Lihat Semua
                    </Link>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#f1f5f9] text-[#3f484a] text-xs font-semibold uppercase tracking-wider">
                            <th class="px-6 py-3 text-left">No. Invoice</th>
                            <th class="px-6 py-3 text-left">Total Harga</th>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="trx in transaksiTerbaru"
                            :key="trx.id"
                            class="border-b border-[#f1f5f9] last:border-0 hover:bg-[#f8f9ff] transition-colors"
                        >
                            <td class="px-6 py-4 font-medium text-[#0b1c30]">{{ trx.nomor_invoice }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block bg-[#e5eeff] text-[#004349] text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ formatRupiah(trx.total_harga) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-[#3f484a]">{{ formatDate(trx.created_at) }}</td>
                            <td class="px-6 py-4">
                                <button class="text-[#6f797a] hover:text-[#004349] transition-colors">
                                    <span class="material-symbols-outlined" style="font-size:20px;">visibility</span>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="transaksiTerbaru.length === 0">
                            <td colspan="4" class="px-6 py-10 text-center text-[#6f797a]">
                                Belum ada transaksi
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Sidebar Kanan -->
            <div class="flex flex-col gap-4">

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-[#e5eeff] shadow-sm p-5">
                    <h4 class="text-base font-semibold text-[#0b1c30] mb-4" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Aksi Cepat
                    </h4>
                    <div class="flex flex-col gap-2">
                        <Link href="/transaksi" class="flex items-center gap-3 px-4 py-3 bg-[#004349] text-white rounded-lg text-sm font-semibold hover:bg-[#0d5c63] transition-colors">
                            <span class="material-symbols-outlined" style="font-size:18px; font-variation-settings: 'FILL' 1;">add_shopping_cart</span>
                            Transaksi Baru (Kasir)
                        </Link>
                        <Link href="/items" class="flex items-center gap-3 px-4 py-3 bg-[#e5eeff] text-[#004349] rounded-lg text-sm font-semibold hover:bg-[#dce9ff] transition-colors">
                            <span class="material-symbols-outlined" style="font-size:18px; font-variation-settings: 'FILL' 1;">inventory_2</span>
                            Kelola Item
                        </Link>
                        <Link href="/mutasi-stok" class="flex items-center gap-3 px-4 py-3 bg-[#e5eeff] text-[#004349] rounded-lg text-sm font-semibold hover:bg-[#dce9ff] transition-colors">
                            <span class="material-symbols-outlined" style="font-size:18px; font-variation-settings: 'FILL' 1;">swap_vert</span>
                            Lihat Mutasi Stok
                        </Link>
                    </div>
                </div>

                <!-- Info Ringkasan -->
                <div class="bg-[#004349] rounded-xl p-5 shadow-sm">
                    <h4 class="text-sm font-semibold text-[#abeef6] uppercase tracking-widest mb-3">
                        Total Pendapatan
                    </h4>
                    <p class="text-3xl font-bold text-white leading-tight">
                        {{ formatRupiah(totalPendapatan) }}
                    </p>
                    <p class="text-xs text-[#90d2da] mt-2">Keseluruhan sejak toko dibuka</p>
                </div>

            </div>
        </section>

    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    font-family: 'Material Symbols Outlined';
}

.zen-stat-card {
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.zen-stat-card:hover {
    transform: translateY(-2px);
}
</style>