<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

// Hapus 'ref' karena kita tidak pakai data dummy lagi
defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Mutasi Stok', href: '/mutasi-stok' },
        ],
    },
});

// INI KUNCI INTEGRASINYA: Menangkap data 'mutasiStok' yang dikirim dari Controller-mu
defineProps({
    mutasiStok: Array
});
</script>

<template>
    <Head title="Mutasi Stok" />
    <div class="flex flex-col gap-4 p-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Mutasi Stok</h1>
        </div>

        <!-- Tabel -->
        <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-sidebar overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr class="text-left text-muted-foreground">
                        <th class="px-4 py-3 font-medium">Kode Item</th>
                        <th class="px-4 py-3 font-medium">Nama Item</th>
                        <th class="px-4 py-3 font-medium">Jenis</th>
                        <th class="px-4 py-3 font-medium">Jumlah</th>
                        <th class="px-4 py-3 font-medium">Stok Sebelum</th>
                        <th class="px-4 py-3 font-medium">Stok Sesudah</th>
                        <th class="px-4 py-3 font-medium">Keterangan</th>
                        <th class="px-4 py-3 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="mutasi in mutasiStok"
                        :key="mutasi.id"
                        class="border-t border-sidebar-border/70 dark:border-sidebar-border hover:bg-gray-50 dark:hover:bg-gray-800"
                    >
                        <td class="px-4 py-3 font-medium">{{ mutasi.kode_item }}</td>
                        <td class="px-4 py-3">{{ mutasi.nama_item }}</td>
                        <td class="px-4 py-3">
                            <span
                                :class="mutasi.jenis_mutasi === 'masuk'
                                    ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                                    : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'"
                                class="px-2 py-1 rounded-full text-xs font-medium"
                            >
                                {{ mutasi.jenis_mutasi === 'masuk' ? '↑ Masuk' : '↓ Keluar' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ mutasi.jumlah }}</td>
                        <td class="px-4 py-3">{{ mutasi.stok_sebelum }}</td>
                        <td class="px-4 py-3">{{ mutasi.stok_sesudah }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ mutasi.keterangan }}</td>
                        <td class="px-4 py-3">{{ mutasi.created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>