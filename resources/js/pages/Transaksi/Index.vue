<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Transaksi', href: '/transaksi' },
        ],
    },
});

const props = defineProps({
    transaksi: Array,
    items: Array,
});

const page = usePage();
const userId = (page.props.auth as any)?.user?.id ?? 1;

// Modal kasir
const showKasir = ref(false);
const keranjang = ref<{ item_id: number; nama_item: string; harga_jual: number; qty: number }[]>([]);
const selectedItemId = ref('');
const selectedQty = ref(1);
const bayar = ref(0);

const totalBelanja = computed(() =>
    keranjang.value.reduce((sum, k) => sum + k.harga_jual * k.qty, 0)
);

const kembalian = computed(() => bayar.value - totalBelanja.value);

function openKasir() {
    keranjang.value = [];
    selectedItemId.value = '';
    selectedQty.value = 1;
    bayar.value = 0;
    showKasir.value = true;
}

function tambahKeKeranjang() {
    if (!selectedItemId.value) return;
    const item: any = props.items?.find((i: any) => i.id === Number(selectedItemId.value));
    if (!item) return;

    keranjang.value.push({
        item_id: item.id,
        nama_item: item.nama_item,
        harga_jual: Number(item.harga_jual),
        qty: selectedQty.value,
    });

    selectedItemId.value = '';
    selectedQty.value = 1;
}

function hapusDariKeranjang(index: number) {
    keranjang.value.splice(index, 1);
}

function simpanTransaksi() {
    router.post('/transaksi', {
        user_id: userId,
        bayar: bayar.value,
        items: keranjang.value.map(k => ({ item_id: k.item_id, qty: k.qty })),
    }, {
        onSuccess: () => { showKasir.value = false; }
    });
}

// Modal Detail
const showDetail = ref(false);
const selectedTrx = ref<any>(null);

function lihatDetail(trx: any) {
    selectedTrx.value = trx;
    showDetail.value = true;
}
</script>

<template>
    <Head title="Transaksi" />
    <div class="flex flex-col gap-4 p-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Daftar Transaksi</h1>
            <button @click="openKasir" class="bg-black text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
                + Transaksi Baru
            </button>
        </div>

        <!-- Tabel -->
        <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-white dark:bg-sidebar overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr class="text-left text-muted-foreground">
                        <th class="px-4 py-3 font-medium">No. Invoice</th>
                        <th class="px-4 py-3 font-medium">Total Harga</th>
                        <th class="px-4 py-3 font-medium">Bayar</th>
                        <th class="px-4 py-3 font-medium">Kembalian</th>
                        <th class="px-4 py-3 font-medium">Tanggal</th>
                        <th class="px-4 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="trx in transaksi"
                        :key="trx.id"
                        class="border-t border-sidebar-border/70 dark:border-sidebar-border hover:bg-gray-50 dark:hover:bg-gray-800"
                    >
                        <td class="px-4 py-3 font-medium">{{ trx.nomor_invoice }}</td>
                        <td class="px-4 py-3">Rp {{ Number(trx.total_harga).toLocaleString('id-ID') }}</td>
                        <td class="px-4 py-3">Rp {{ Number(trx.bayar).toLocaleString('id-ID') }}</td>
                        <td class="px-4 py-3">Rp {{ Number(trx.kembalian).toLocaleString('id-ID') }}</td>
                        <td class="px-4 py-3">{{ new Date(trx.tanggal_transaksi).toLocaleDateString('id-ID') }}</td>
                        <td class="px-4 py-3">
                            <button @click="lihatDetail(trx)" class="text-blue-600 hover:underline text-xs">Detail</button>
                        </td>
                    </tr>
                    <tr v-if="!transaksi || transaksi.length === 0">
                        <td colspan="6" class="px-4 py-6 text-center text-muted-foreground">Belum ada transaksi</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal Kasir / Transaksi Baru -->
        <div v-if="showKasir" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-sidebar rounded-xl p-6 w-full max-w-lg shadow-xl">
                <h2 class="text-lg font-bold mb-4">Transaksi Baru</h2>

                <!-- Pilih item -->
                <div class="flex gap-2 mb-3">
                    <select v-model="selectedItemId" class="flex-1 border rounded-lg px-3 py-2 text-sm">
                        <option value="" disabled>Pilih Item</option>
                        <option v-for="item in items" :key="item.id" :value="item.id">
                            {{ item.nama_item }} (Stok: {{ item.stok }})
                        </option>
                    </select>
                    <input v-model.number="selectedQty" type="number" min="1" class="w-20 border rounded-lg px-3 py-2 text-sm" />
                    <button @click="tambahKeKeranjang" class="bg-black text-white px-3 py-2 rounded-lg text-sm hover:bg-gray-800">Tambah</button>
                </div>

                <!-- Keranjang -->
                <div class="border rounded-lg divide-y mb-3 max-h-48 overflow-y-auto">
                    <div v-if="keranjang.length === 0" class="p-3 text-sm text-muted-foreground text-center">
                        Belum ada item di keranjang
                    </div>
                    <div v-for="(k, index) in keranjang" :key="index" class="flex items-center justify-between p-2 text-sm">
                        <div>
                            <p class="font-medium">{{ k.nama_item }}</p>
                            <p class="text-muted-foreground">{{ k.qty }} x Rp {{ k.harga_jual.toLocaleString('id-ID') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-medium">Rp {{ (k.qty * k.harga_jual).toLocaleString('id-ID') }}</span>
                            <button @click="hapusDariKeranjang(index)" class="text-red-600 text-xs hover:underline">Hapus</button>
                        </div>
                    </div>
                </div>

                <!-- Total & Bayar -->
                <div class="flex flex-col gap-2 text-sm border-t pt-3">
                    <div class="flex justify-between font-medium">
                        <span>Total Belanja</span>
                        <span>Rp {{ totalBelanja.toLocaleString('id-ID') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <label>Bayar</label>
                        <input v-model.number="bayar" type="number" class="w-40 border rounded-lg px-3 py-2 text-sm text-right" placeholder="0" />
                    </div>
                    <div class="flex justify-between font-medium">
                        <span>Kembalian</span>
                        <span :class="kembalian < 0 ? 'text-red-600' : 'text-green-600'">
                            Rp {{ kembalian.toLocaleString('id-ID') }}
                        </span>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button @click="showKasir = false" class="px-4 py-2 text-sm rounded-lg border hover:bg-gray-50">Batal</button>
                    <button
                        @click="simpanTransaksi"
                        :disabled="keranjang.length === 0 || kembalian < 0"
                        class="px-4 py-2 text-sm rounded-lg bg-black text-white hover:bg-gray-800 disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Detail -->
        <div v-if="showDetail && selectedTrx" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-sidebar rounded-xl p-6 w-full max-w-md shadow-xl">
                <h2 class="text-lg font-bold mb-4">Detail Transaksi</h2>
                <div class="flex flex-col gap-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">No. Invoice</span>
                        <span class="font-medium">{{ selectedTrx.nomor_invoice }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Tanggal</span>
                        <span>{{ new Date(selectedTrx.tanggal_transaksi).toLocaleDateString('id-ID') }}</span>
                    </div>
                    <hr class="my-2" />
                    <div v-if="selectedTrx.detail_transaksi && selectedTrx.detail_transaksi.length > 0" class="flex flex-col gap-1">
                        <div v-for="d in selectedTrx.detail_transaksi" :key="d.id" class="flex justify-between">
                            <span>{{ d.nama_item }} x{{ d.qty }}</span>
                            <span>Rp {{ Number(d.subtotal).toLocaleString('id-ID') }}</span>
                        </div>
                    </div>
                    <hr class="my-2" />
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Total Harga</span>
                        <span>Rp {{ Number(selectedTrx.total_harga).toLocaleString('id-ID') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted-foreground">Bayar</span>
                        <span>Rp {{ Number(selectedTrx.bayar).toLocaleString('id-ID') }}</span>
                    </div>
                    <div class="flex justify-between font-medium">
                        <span>Kembalian</span>
                        <span class="text-green-600">Rp {{ Number(selectedTrx.kembalian).toLocaleString('id-ID') }}</span>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button @click="showDetail = false" class="px-4 py-2 text-sm rounded-lg border hover:bg-gray-50">Tutup</button>
                </div>
            </div>
        </div>

    </div>
</template>