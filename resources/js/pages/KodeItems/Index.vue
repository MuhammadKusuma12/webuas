<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Kode Items', href: '/kode-items' },
        ],
    },
});

// Data dummy sementara
const kodeItems = ref([
    { id: 1, kode: 'KOI', nama_jenis: 'Ikan Koi', deskripsi: 'Berbagai jenis ikan koi' },
    { id: 2, kode: 'PKN', nama_jenis: 'Pakan', deskripsi: 'Pakan ikan koi' },
    { id: 3, kode: 'ACC', nama_jenis: 'Aksesoris', deskripsi: 'Aksesoris kolam koi' },
    { id: 4, kode: 'OBT', nama_jenis: 'Obat', deskripsi: 'Obat dan vitamin koi' },
]);
</script>

<template>
    <Head title="Kode Items" />
    <div class="flex flex-col gap-4 p-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Kode Items</h1>
            <button class="bg-black text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
                + Tambah Kode
            </button>
        </div>

        <!-- Tabel -->
        <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-sidebar overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr class="text-left text-muted-foreground">
                        <th class="px-4 py-3 font-medium">No</th>
                        <th class="px-4 py-3 font-medium">Kode</th>
                        <th class="px-4 py-3 font-medium">Nama Jenis</th>
                        <th class="px-4 py-3 font-medium">Deskripsi</th>
                        <th class="px-4 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(item, index) in kodeItems"
                        :key="item.id"
                        class="border-t border-sidebar-border/70 dark:border-sidebar-border hover:bg-gray-50 dark:hover:bg-gray-800"
                    >
                        <td class="px-4 py-3 text-muted-foreground">{{ index + 1 }}</td>
                        <td class="px-4 py-3 font-medium">{{ item.kode }}</td>
                        <td class="px-4 py-3">{{ item.nama_jenis }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ item.deskripsi }}</td>
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