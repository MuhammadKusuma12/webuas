<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Items', href: '/items' },
        ],
    },
});

const props = defineProps({
    items: Array,
    kodeItems: Array,
});

const showModal = ref(false);
const isEdit = ref(false);
const showDeleteConfirm = ref(false);
const selectedId = ref<number | null>(null);

const form = ref({
    id: 0,
    kode_item_id: '',
    kode_item: '',
    nama_item: '',
    harga_beli: 0,
    harga_jual: 0,
    stok: 0,
    stok_minimum: 0,
});

function openTambah() {
    isEdit.value = false;
    form.value = { id: 0, kode_item_id: '', kode_item: '', nama_item: '', harga_beli: 0, harga_jual: 0, stok: 0, stok_minimum: 0 };
    showModal.value = true;
}

function openEdit(item: any) {
    isEdit.value = true;
    form.value = {
        id: item.id,
        kode_item_id: item.kode_item_id,
        kode_item: item.kode_item?.kode_prefix ?? '',
        nama_item: item.nama_item,
        harga_beli: item.harga_beli,
        harga_jual: item.harga_jual,
        stok: item.stok,
        stok_minimum: item.stok_minimum,
    };
    showModal.value = true;
}

function simpan() {
    if (isEdit.value) {
        router.put(`/items/${form.value.id}`, form.value, {
            onSuccess: () => { showModal.value = false; }
        });
    } else {
        router.post('/items', form.value, {
            onSuccess: () => { showModal.value = false; }
        });
    }
}

function konfirmasiHapus(id: number) {
    selectedId.value = id;
    showDeleteConfirm.value = true;
}

function hapus() {
    if (selectedId.value) {
        router.delete(`/items/${selectedId.value}`, {
            onSuccess: () => { showDeleteConfirm.value = false; }
        });
    }
}
</script>

<template>
    <Head title="Items" />
    <div class="flex flex-col gap-4 p-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Daftar Ikan Koi</h1>
            <button @click="openTambah" class="bg-black text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
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
                        <th class="px-4 py-3 font-medium">Harga Beli</th>
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
                        <td class="px-4 py-3">{{ item.kode_item?.kode_prefix }}</td>
                        <td class="px-4 py-3 font-medium">{{ item.nama_item }}</td>
                        <td class="px-4 py-3">Rp {{ Number(item.harga_beli).toLocaleString('id-ID') }}</td>
                        <td class="px-4 py-3">Rp {{ Number(item.harga_jual).toLocaleString('id-ID') }}</td>
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
                                <button @click="openEdit(item)" class="text-blue-600 hover:underline text-xs">Edit</button>
                                <button @click="konfirmasiHapus(item.id)" class="text-red-600 hover:underline text-xs">Hapus</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah/Edit -->
        <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-sidebar rounded-xl p-6 w-full max-w-md shadow-xl">
                <h2 class="text-lg font-bold mb-4">{{ isEdit ? 'Edit Item' : 'Tambah Item' }}</h2>
                <div class="flex flex-col gap-3">
                    <div>
                        <label class="text-sm font-medium">Kategori</label>
                        <select v-model="form.kode_item_id" class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
                            <option value="" disabled>Pilih Kategori</option>
                            <option v-for="kategori in kodeItems" :key="kategori.id" :value="kategori.id">
                                {{ kategori.nama_kode }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Kode Item</label>
                        <input v-model="form.kode_item" class="w-full mt-1 border rounded-lg px-3 py-2 text-sm" placeholder="Contoh: KOI-001" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Nama Item</label>
                        <input v-model="form.nama_item" class="w-full mt-1 border rounded-lg px-3 py-2 text-sm" placeholder="Contoh: Koi Kohaku" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium">Harga Beli</label>
                            <input v-model="form.harga_beli" type="number" class="w-full mt-1 border rounded-lg px-3 py-2 text-sm" placeholder="0" />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Harga Jual</label>
                            <input v-model="form.harga_jual" type="number" class="w-full mt-1 border rounded-lg px-3 py-2 text-sm" placeholder="0" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium">Stok</label>
                            <input v-model="form.stok" type="number" class="w-full mt-1 border rounded-lg px-3 py-2 text-sm" placeholder="0" />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Stok Minimum</label>
                            <input v-model="form.stok_minimum" type="number" class="w-full mt-1 border rounded-lg px-3 py-2 text-sm" placeholder="0" />
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button @click="showModal = false" class="px-4 py-2 text-sm rounded-lg border hover:bg-gray-50">Batal</button>
                    <button @click="simpan" class="px-4 py-2 text-sm rounded-lg bg-black text-white hover:bg-gray-800">Simpan</button>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-sidebar rounded-xl p-6 w-full max-w-sm shadow-xl">
                <h2 class="text-lg font-bold mb-2">Hapus Data?</h2>
                <p class="text-sm text-muted-foreground mb-4">Data yang dihapus tidak bisa dikembalikan.</p>
                <div class="flex justify-end gap-2">
                    <button @click="showDeleteConfirm = false" class="px-4 py-2 text-sm rounded-lg border hover:bg-gray-50">Batal</button>
                    <button @click="hapus" class="px-4 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700">Hapus</button>
                </div>
            </div>
        </div>

    </div>
</template>