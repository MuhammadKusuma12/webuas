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

function formatRupiah(num: number) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}
</script>

<template>
    <Head title="Items" />
    <div class="flex flex-col gap-5 p-6" style="font-family: 'Inter', sans-serif;">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0b1c30;">
                    Daftar Ikan Koi
                </h1>
                <p style="font-size: 0.85rem; color: #3f484a; margin-top: 2px;">Kelola stok dan data ikan koi</p>
            </div>
            <button
                @click="openTambah"
                style="background: #004349; color: #fff; padding: 9px 18px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer;"
                class="hover:opacity-90 transition-opacity"
            >
                + Tambah Item
            </button>
        </div>

        <!-- Tabel -->
        <div style="background: #fff; border: 1px solid #e5eeff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
            <table class="w-full" style="font-size: 0.875rem; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f1f5f9;">
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Kode</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Nama Item</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Harga Beli</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Harga Jual</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Stok</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Status</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.06em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="item in items"
                        :key="item.id"
                        style="border-top: 1px solid #f1f5f9; transition: background 0.15s;"
                        class="hover:bg-[#f8f9ff]"
                    >
                        <td style="padding: 12px 16px; color: #3f484a; font-size: 0.8rem; font-family: monospace;">
                            {{ item.kode_item?.kode_prefix }}
                        </td>
                        <td style="padding: 12px 16px; font-weight: 600; color: #0b1c30;">{{ item.nama_item }}</td>
                        <td style="padding: 12px 16px; color: #3f484a;">{{ formatRupiah(item.harga_beli) }}</td>
                        <td style="padding: 12px 16px; color: #0b1c30; font-weight: 600;">{{ formatRupiah(item.harga_jual) }}</td>
                        <td style="padding: 12px 16px; color: #0b1c30; font-weight: 600;">{{ item.stok }}</td>
                        <td style="padding: 12px 16px;">
                            <span
                                :style="item.stok <= item.stok_minimum
                                    ? 'background: #ffdad6; color: #ba1a1a;'
                                    : 'background: #dce9ff; color: #004349;'"
                                style="padding: 3px 10px; border-radius: 999px; font-size: 0.72rem; font-weight: 600;"
                            >
                                {{ item.stok <= item.stok_minimum ? 'Stok Menipis' : 'Tersedia' }}
                            </span>
                        </td>
                        <td style="padding: 12px 16px;">
                            <div class="flex gap-3">
                                <button
                                    @click="openEdit(item)"
                                    style="color: #004349; font-size: 0.8rem; font-weight: 600; background: none; border: none; cursor: pointer;"
                                    class="hover:underline"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="konfirmasiHapus(item.id)"
                                    style="color: #ba1a1a; font-size: 0.8rem; font-weight: 600; background: none; border: none; cursor: pointer;"
                                    class="hover:underline"
                                >
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!items || items.length === 0">
                        <td colspan="7" style="padding: 40px; text-align: center; color: #6f797a;">
                            Belum ada data item.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah/Edit -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50" style="background: rgba(11,28,48,0.5);">
            <div style="background: #fff; border-radius: 16px; padding: 28px; width: 100%; max-width: 460px; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.1rem; font-weight: 700; color: #0b1c30; margin-bottom: 20px;">
                    {{ isEdit ? 'Edit Item' : 'Tambah Item' }}
                </h2>
                <div class="flex flex-col gap-3">
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 600; color: #3f484a; display: block; margin-bottom: 4px;">Kategori</label>
                        <select v-model="form.kode_item_id"
                            class="focus:outline-none focus:ring-2 focus:ring-[#004349]/20 focus:border-[#004349]"
                            style="width: 100%; border: 1px solid #bfc8c9; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; color: #0b1c30; box-sizing: border-box;">
                            <option value="" disabled>Pilih Kategori</option>
                            <option v-for="kategori in kodeItems" :key="kategori.id" :value="kategori.id">
                                {{ kategori.nama_kode }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 600; color: #3f484a; display: block; margin-bottom: 4px;">Kode Item</label>
                        <input v-model="form.kode_item" placeholder="Contoh: KOI-001"
                            class="focus:outline-none focus:ring-2 focus:ring-[#004349]/20 focus:border-[#004349]"
                            style="width: 100%; border: 1px solid #bfc8c9; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; color: #0b1c30; box-sizing: border-box;" />
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 600; color: #3f484a; display: block; margin-bottom: 4px;">Nama Item</label>
                        <input v-model="form.nama_item" placeholder="Contoh: Koi Kohaku"
                            class="focus:outline-none focus:ring-2 focus:ring-[#004349]/20 focus:border-[#004349]"
                            style="width: 100%; border: 1px solid #bfc8c9; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; color: #0b1c30; box-sizing: border-box;" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 600; color: #3f484a; display: block; margin-bottom: 4px;">Harga Beli</label>
                            <input v-model="form.harga_beli" type="number"
                                class="focus:outline-none focus:ring-2 focus:ring-[#004349]/20 focus:border-[#004349]"
                                style="width: 100%; border: 1px solid #bfc8c9; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; color: #0b1c30; box-sizing: border-box;" />
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 600; color: #3f484a; display: block; margin-bottom: 4px;">Harga Jual</label>
                            <input v-model="form.harga_jual" type="number"
                                class="focus:outline-none focus:ring-2 focus:ring-[#004349]/20 focus:border-[#004349]"
                                style="width: 100%; border: 1px solid #bfc8c9; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; color: #0b1c30; box-sizing: border-box;" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 600; color: #3f484a; display: block; margin-bottom: 4px;">Stok</label>
                            <input v-model="form.stok" type="number"
                                class="focus:outline-none focus:ring-2 focus:ring-[#004349]/20 focus:border-[#004349]"
                                style="width: 100%; border: 1px solid #bfc8c9; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; color: #0b1c30; box-sizing: border-box;" />
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 600; color: #3f484a; display: block; margin-bottom: 4px;">Stok Minimum</label>
                            <input v-model="form.stok_minimum" type="number"
                                class="focus:outline-none focus:ring-2 focus:ring-[#004349]/20 focus:border-[#004349]"
                                style="width: 100%; border: 1px solid #bfc8c9; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; color: #0b1c30; box-sizing: border-box;" />
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-2" style="margin-top: 20px;">
                    <button @click="showModal = false"
                        style="padding: 8px 18px; border-radius: 8px; border: 1px solid #bfc8c9; font-size: 0.875rem; font-weight: 600; color: #3f484a; background: #fff; cursor: pointer;"
                        class="hover:bg-[#f8f9ff]">
                        Batal
                    </button>
                    <button @click="simpan"
                        style="padding: 8px 18px; border-radius: 8px; background: #004349; color: #fff; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer;"
                        class="hover:opacity-90">
                        Simpan
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div v-if="showDeleteConfirm" class="fixed inset-0 flex items-center justify-center z-50" style="background: rgba(11,28,48,0.5);">
            <div style="background: #fff; border-radius: 16px; padding: 28px; width: 100%; max-width: 360px; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
                <div style="width: 44px; height: 44px; background: #ffdad6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
                    <span class="material-symbols-outlined" style="font-size: 22px; color: #ba1a1a; font-variation-settings: 'FILL' 1;">delete</span>
                </div>
                <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.05rem; font-weight: 700; color: #0b1c30; margin-bottom: 6px;">
                    Hapus Data?
                </h2>
                <p style="font-size: 0.875rem; color: #3f484a; margin-bottom: 20px; line-height: 1.5;">
                    Data yang dihapus tidak bisa dikembalikan.
                </p>
                <div class="flex justify-end gap-2">
                    <button @click="showDeleteConfirm = false"
                        style="padding: 8px 18px; border-radius: 8px; border: 1px solid #bfc8c9; font-size: 0.875rem; font-weight: 600; color: #3f484a; background: #fff; cursor: pointer;"
                        class="hover:bg-[#f8f9ff]">
                        Batal
                    </button>
                    <button @click="hapus"
                        style="padding: 8px 18px; border-radius: 8px; background: #ba1a1a; color: #fff; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer;"
                        class="hover:opacity-90">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    font-family: 'Material Symbols Outlined';
}
</style>