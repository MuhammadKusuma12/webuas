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
    return props.mutasiStok?.filter((m: any) => m.jenis_mutasi === filterJenis.value);
});
</script>

<template>
    <Head title="Mutasi Stok" />
    <div class="flex flex-col gap-5 p-6" style="font-family: 'Inter', sans-serif;">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0b1c30;">Mutasi Stok</h1>
                <p style="font-size: 0.85rem; color: #3f484a; margin-top: 2px;">Riwayat perubahan stok masuk dan keluar</p>
            </div>
            <select
                v-model="filterJenis"
                class="focus:outline-none focus:ring-2 focus:ring-[#004349]/20 focus:border-[#004349]"
                style="border: 1px solid #bfc8c9; border-radius: 8px; padding: 8px 14px; font-size: 0.875rem; color: #0b1c30; background: #fff; cursor: pointer;"
            >
                <option value="semua">Semua Jenis</option>
                <option value="masuk">↑ Masuk</option>
                <option value="keluar">↓ Keluar</option>
            </select>
        </div>

        <!-- Tabel -->
        <div style="background: #fff; border: 1px solid #e5eeff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <table class="w-full" style="font-size: 0.875rem; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Kode Item</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Nama Item</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Jenis</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Jumlah</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Stok Sebelum</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Stok Sesudah</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Keterangan</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="mutasi in filtered"
                        :key="mutasi.id"
                        style="border-top: 1px solid #f1f5f9; transition: background 0.15s;"
                        class="hover:bg-[#f8f9ff]"
                    >
                        <td style="padding: 12px 16px;">
                            <span v-if="mutasi.kode_item && mutasi.kode_item !== '-'"
                                style="background: #dce9ff; color: #004349; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; font-family: monospace;">
                                {{ mutasi.kode_item }}
                            </span>
                            <span v-else style="color: #6f797a; font-size: 0.75rem;">-</span>
                        </td>
                        <td style="padding: 12px 16px; font-weight: 600; color: #0b1c30;">{{ mutasi.nama_item }}</td>
                        <td style="padding: 12px 16px;">
                            <span
                                :style="mutasi.jenis_mutasi === 'masuk'
                                    ? 'background: #dce9ff; color: #004349;'
                                    : 'background: #ffdad6; color: #ba1a1a;'"
                                style="padding: 3px 10px; border-radius: 999px; font-size: 0.72rem; font-weight: 700;"
                            >
                                {{ mutasi.jenis_mutasi === 'masuk' ? '↑ Masuk' : '↓ Keluar' }}
                            </span>
                        </td>
                        <td style="padding: 12px 16px; font-weight: 700; color: #0b1c30;">{{ mutasi.jumlah }}</td>
                        <td style="padding: 12px 16px; color: #3f484a;">{{ mutasi.stok_sebelum }}</td>
                        <td style="padding: 12px 16px; color: #3f484a;">{{ mutasi.stok_sesudah }}</td>
                        <td style="padding: 12px 16px; color: #6f797a; font-size: 0.8rem;">{{ mutasi.keterangan }}</td>
                        <td style="padding: 12px 16px; color: #3f484a; font-size: 0.8rem;">
                            {{ new Date(mutasi.created_at).toLocaleDateString('id-ID') }}
                        </td>
                    </tr>
                    <tr v-if="!filtered || filtered.length === 0">
                        <td colspan="8" style="padding: 40px; text-align: center; color: #6f797a;">
                            Tidak ada data mutasi stok.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>