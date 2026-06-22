<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard } from '@/routes';

defineProps<{
    totalStok: number;
    totalTransaksi: number;
    totalJenis: number;
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

        <header class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <h2 class="text-[32px] font-semibold leading-10 text-[#0b1c30]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Dashboard Overview
                </h2>
                <p class="text-base text-[#3f484a] mt-1" style="font-family: 'Inter', sans-serif;">
                    Welcome back, {{ $page.props.auth.user.nama || 'Administrator' }}. Here is your shop summary.
                </p>
            </div>
            <div class="flex gap-3">
                <Link href="/" class="flex items-center gap-2 px-4 py-2 bg-[#dce9ff] border border-[#bfc8c9] rounded-lg text-sm font-semibold text-[#0b1c30] hover:bg-[#cce0ff] transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[#0b1c30]" style="font-size:18px;">storefront</span>
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
                    Input New Fish
                </Link>
            </div>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="zen-stat-card bg-white rounded-xl p-6 shadow-sm border border-[#e5eeff] border-l-4 border-l-[#004349] flex flex-col justify-between h-44 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="bg-[#abeef6] p-3 rounded-lg">
                        <span class="material-symbols-outlined text-[#004349]" style="font-variation-settings: 'FILL' 1; font-size:22px;">inventory_2</span>
                    </div>
                    <span class="text-xs font-semibold text-[#004f55] tracking-wide">+12% vs last month</span>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#3f484a]" style="font-family: 'Inter', sans-serif;">Total Stok Ikan</p>
                    <p class="text-5xl font-bold text-[#004349] mt-1 leading-none" style="font-family: 'Plus Jakarta Sans', sans-serif;">{{ totalStok }}</p>
                </div>
            </div>

            <div class="zen-stat-card bg-white rounded-xl p-6 shadow-sm border border-[#e5eeff] border-l-4 border-l-[#934a29] flex flex-col justify-between h-44 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="bg-[#ffdbce] p-3 rounded-lg">
                        <span class="material-symbols-outlined text-[#934a29]" style="font-variation-settings: 'FILL' 1; font-size:22px;">payments</span>
                    </div>
                    <span class="text-xs font-semibold text-[#934a29] tracking-wide">Daily target: 75%</span>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#3f484a]" style="font-family: 'Inter', sans-serif;">Total Transaksi</p>
                    <p class="text-5xl font-bold text-[#934a29] mt-1 leading-none" style="font-family: 'Plus Jakarta Sans', sans-serif;">{{ totalTransaksi }}</p>
                </div>
            </div>

            <div class="zen-stat-card bg-white rounded-xl p-6 shadow-sm border border-[#e5eeff] border-l-4 border-l-[#004443] flex flex-col justify-between h-44 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="bg-[#98f2f0] p-3 rounded-lg">
                        <span class="material-symbols-outlined text-[#004443]" style="font-variation-settings: 'FILL' 1; font-size:22px;">set_meal</span>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-[#3f484a]" style="font-family: 'Inter', sans-serif;">Total Jenis Koi</p>
                    <p class="text-5xl font-bold text-[#004443] mt-1 leading-none" style="font-family: 'Plus Jakarta Sans', sans-serif;">{{ totalJenis }}</p>
                </div>
            </div>

        </section>

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white rounded-xl border border-[#e5eeff] shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-[#e5eeff]">
                    <h3 class="text-lg font-semibold text-[#0b1c30]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Transaksi Terbaru
                    </h3>
                    <button class="text-sm font-medium text-[#004349] hover:underline" style="font-family: 'Inter', sans-serif;">
                        View All Records
                    </button>
                </div>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#f1f5f9] text-[#3f484a] text-xs font-semibold uppercase tracking-wider">
                            <th class="px-6 py-3 text-left" style="font-family: 'Inter', sans-serif;">Kode Transaksi</th>
                            <th class="px-6 py-3 text-left" style="font-family: 'Inter', sans-serif;">Total Harga</th>
                            <th class="px-6 py-3 text-left" style="font-family: 'Inter', sans-serif;">Tanggal</th>
                            <th class="px-6 py-3 text-left" style="font-family: 'Inter', sans-serif;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="trx in transaksiTerbaru"
                            :key="trx.id"
                            class="border-b border-[#f1f5f9] last:border-0 hover:bg-[#f8f9ff] transition-colors"
                        >
                            <td class="px-6 py-5 font-medium text-[#0b1c30]" style="font-family: 'Inter', sans-serif;">
                                {{ trx.nomor_invoice }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-block bg-[#fd9e77] text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ formatRupiah(trx.total_harga) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-[#3f484a]" style="font-family: 'Inter', sans-serif;">
                                {{ formatDate(trx.created_at) }}
                            </td>
                            <td class="px-6 py-5">
                                <button class="text-[#6f797a] hover:text-[#004349] transition-colors">
                                    <span class="material-symbols-outlined" style="font-size:20px;">visibility</span>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="transaksiTerbaru.length === 0">
                            <td colspan="4" class="px-6 py-10 text-center text-[#6f797a]" style="font-family: 'Inter', sans-serif;">
                                Belum ada transaksi
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-6">

                <div class="relative rounded-xl overflow-hidden h-48 bg-[#004349] shadow-md flex flex-col justify-end p-5">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#002023]/90 via-[#004349]/50 to-transparent z-10"></div>
                    <div class="relative z-20">
                        <span class="inline-block bg-[#934a29] text-white text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded mb-2">
                            Premium Stock
                        </span>
                        <h4 class="text-lg font-bold text-white leading-tight" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                            Kohaku Grade AAA
                        </h4>
                        <p class="text-xs text-[#abeef6] mt-0.5" style="font-family: 'Inter', sans-serif;">
                            Latest addition. Imported from Niigata.
                        </p>
                        <button class="mt-3 w-full bg-white text-[#004349] text-sm font-semibold py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-[#abeef6] transition-colors">
                            <span class="material-symbols-outlined" style="font-size:16px;">photo_library</span>
                            View Gallery
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-[#e5eeff] p-5 shadow-sm border-t-4 border-t-[#004443]">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-[#004349]" style="font-size:20px;">trending_up</span>
                        <h4 class="text-base font-semibold text-[#0b1c30]" style="font-family: 'Plus Jakarta Sans', sans-serif;">Market Trends</h4>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm text-[#3f484a]" style="font-family: 'Inter', sans-serif;">Koi Food Sales</span>
                                <span class="text-sm font-bold text-[#004349]">+15.4%</span>
                            </div>
                            <div class="w-full h-2 bg-[#e5eeff] rounded-full overflow-hidden">
                                <div class="h-full bg-[#004349] rounded-full" style="width: 75%;"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm text-[#3f484a]" style="font-family: 'Inter', sans-serif;">Auction Activity</span>
                                <span class="text-sm font-bold text-[#934a29]">+8.2%</span>
                            </div>
                            <div class="w-full h-2 bg-[#ffdbce] rounded-full overflow-hidden">
                                <div class="h-full bg-[#934a29] rounded-full" style="width: 52%;"></div>
                            </div>
                        </div>
                    </div>
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