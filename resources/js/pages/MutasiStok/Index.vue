<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Mutasi Stok', href: '/mutasi-stok' },
        ],
    },
});

const props = defineProps({
    mutasiStok: Array,
});

const filterJenis = ref('semua');

const filtered = computed(() => {
    if (filterJenis.value === 'semua') return props.mutasiStok;
    return props.mutasiStok.filter((m: any) => m.jenis_mutasi === filterJenis.value);
});
</script>

<template>
    <Head title="Mutasi Stok" />
    <div class="flex flex-col gap-4 p-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Mutasi Stok</h1>
            <select v-model="filterJenis" class="border rounded-lg px-3 py-2 text-sm">
                <option value="semua">Semua</option>
                <option value="masuk">Masuk</option>
                <option value="keluar">Keluar</option>
            </select>
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
                        v-for="mutasi in filtered"
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
                    <tr v-if="filtered.length === 0">
                        <td colspan="8" class="px-4 py-6 text-center text-muted-foreground">Tidak ada data</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>