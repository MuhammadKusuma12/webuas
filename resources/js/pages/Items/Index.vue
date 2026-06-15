<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Items', href: '/items' },
        ],
    },
});

// Data dummy sementara
const items = ref([
    { id: 1, kode_item: 'KOI-001', nama_item: 'Koi Kohaku', harga_jual: 150000, stok: 10, stok_minimum: 3 },
    { id: 2, kode_item: 'KOI-002', nama_item: 'Koi Sanke', harga_jual: 200000, stok: 5, stok_minimum: 2 },
    { id: 3, kode_item: 'KOI-003', nama_item: 'Koi Showa', harga_jual: 250000, stok: 2, stok_minimum: 3 },
]);
</script>

<template>
    <Head title="Items" />
    <div class="flex flex-col gap-4 p-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Daftar Ikan Koi</h1>
            <button class="bg-black text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
                + Tambah Item
            </button>
        </div>

        <!-- Tabel -->
        <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-sidebar overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr class="text-left text-muted-foreground">
                        <th class="px-4 py-3 font-medium">Kode</th>
                        <th class="px-4 py-3 font-medium">Nama Item</th>
                        <th class="px-4 py-3 font-medium">Harga Jual</th>
                        <th class="px-4 py-3 font-medium">Stok</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="item in items"
                        :key="item.id"
                        class="border-t border-sidebar-border/70 dark:border-sidebar-border hover:bg-gray-50 dark:hover:bg-gray-800"
                    >
                        <td class="px-4 py-3">{{ item.kode_item }}</td>
                        <td class="px-4 py-3 font-medium">{{ item.nama_item }}</td>
                        <td class="px-4 py-3">Rp {{ item.harga_jual.toLocaleString('id-ID') }}</td>
                        <td class="px-4 py-3">{{ item.stok }}</td>
                        <td class="px-4 py-3">
                            <span
                                :class="item.stok <= item.stok_minimum
                                    ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'
                                    : 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'"
                                class="px-2 py-1 rounded-full text-xs font-medium"
                            >
                                {{ item.stok <= item.stok_minimum ? 'Stok Menipis' : 'Tersedia' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <button class="text-blue-600 hover:underline text-xs">Edit</button>
                                <button class="text-red-600 hover:underline text-xs">Hapus</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>