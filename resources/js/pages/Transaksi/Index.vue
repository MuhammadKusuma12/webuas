<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Transaksi', href: '/transaksi' },
        ],
    },
});

// Data dummy sementara
const transaksis = ref([
    { id: 1, nomor_invoice: 'INV-001', nama_kasir: 'Admin', total_harga: 300000, bayar: 300000, kembalian: 0, tanggal_transaksi: '2026-06-12' },
    { id: 2, nomor_invoice: 'INV-002', nama_kasir: 'Pegawai Satu', total_harga: 450000, bayar: 500000, kembalian: 50000, tanggal_transaksi: '2026-06-13' },
    { id: 3, nomor_invoice: 'INV-003', nama_kasir: 'Pegawai Satu', total_harga: 215000, bayar: 215000, kembalian: 0, tanggal_transaksi: '2026-06-14' },
]);
</script>

<template>
    <Head title="Transaksi" />
    <div class="flex flex-col gap-4 p-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Daftar Transaksi</h1>
            <button class="bg-black text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
                + Transaksi Baru
            </button>
        </div>

        <!-- Tabel -->
        <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-sidebar overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr class="text-left text-muted-foreground">
                        <th class="px-4 py-3 font-medium">No. Invoice</th>
                        <th class="px-4 py-3 font-medium">Kasir</th>
                        <th class="px-4 py-3 font-medium">Total Harga</th>
                        <th class="px-4 py-3 font-medium">Bayar</th>
                        <th class="px-4 py-3 font-medium">Kembalian</th>
                        <th class="px-4 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="trx in transaksis"
                        :key="trx.id"
                        class="border-t border-sidebar-border/70 dark:border-sidebar-border hover:bg-gray-50 dark:hover:bg-gray-800"
                    >
                        <td class="px-4 py-3 font-medium">{{ trx.nomor_invoice }}</td>
                        <td class="px-4 py-3">{{ trx.nama_kasir }}</td>
                        <td class="px-4 py-3">Rp {{ trx.total_harga.toLocaleString('id-ID') }}</td>
                        <td class="px-4 py-3">Rp {{ trx.bayar.toLocaleString('id-ID') }}</td>
                        <td class="px-4 py-3">Rp {{ trx.kembalian.toLocaleString('id-ID') }}</td>
                        <td class="px-4 py-3">{{ trx.tanggal_transaksi }}</td>
                        <td class="px-4 py-3">
                            <button class="text-blue-600 hover:underline text-xs">Detail</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>