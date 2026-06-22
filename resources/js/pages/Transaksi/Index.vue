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

const showDetail = ref(false);
const selectedTrx = ref<any>(null);

function lihatDetail(trx: any) {
    selectedTrx.value = trx;
    showDetail.value = true;
}

// LOGIKA BARU: Fungsi untuk tombol Terima dan Tolak
function terimaPesanan(id: number) {
    if (confirm('Validasi pembayaran berhasil? Pesanan ini akan diselesaikan.')) {
        router.post(`/transaksi/${id}/terima`);
    }
}

function tolakPesanan(id: number) {
    if (confirm('Tolak pesanan ini? Stok barang akan dikembalikan secara otomatis.')) {
        router.post(`/transaksi/${id}/tolak`);
    }
}
</script>

<template>
    <Head title="Transaksi" />
    <div class="flex flex-col gap-5 p-6" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;">

        <div class="flex items-center justify-between">
            <div>
                <h1 style="font-size: 1.5rem; font-weight: 700; color: #0b1c30;">Daftar Transaksi</h1>
                <p style="font-size: 0.85rem; color: #3f484a; margin-top: 2px;">Riwayat dan manajemen transaksi penjualan</p>
            </div>
            <button
                @click="openKasir"
                style="background: #004349; color: #fff; padding: 9px 18px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer;"
                class="hover:opacity-90 transition-opacity"
            >
                + Transaksi Baru (Kasir)
            </button>
        </div>

        <div style="background: #fff; border: 1px solid #bfc8c9; border-radius: 12px; overflow: hidden; overflow-x: auto;">
            <table class="w-full" style="font-size: 0.875rem; border-collapse: collapse; min-width: 800px;">
                <thead>
                    <tr style="background: #eff4ff;">
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.8rem; text-transform: uppercase;">No. Invoice</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.8rem; text-transform: uppercase;">Status</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.8rem; text-transform: uppercase;">Total Harga</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #3f484a; font-size: 0.8rem; text-transform: uppercase;">Tanggal</th>
                        <th style="padding: 12px 16px; text-align: center; font-weight: 600; color: #3f484a; font-size: 0.8rem; text-transform: uppercase;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="trx in transaksi"
                        :key="trx.id"
                        style="border-top: 1px solid #e5eeff; transition: background 0.15s;"
                        class="hover:bg-[#f8f9ff]"
                    >
                        <td style="padding: 12px 16px;">
                            <span style="font-weight: 700; color: #004349; font-family: monospace; font-size: 0.85rem;">{{ trx.nomor_invoice }}</span>
                            <div style="font-size: 0.7rem; color: #90a4b4; margin-top: 2px;">
                                Sumber: {{ trx.sumber === 'online' ? '🌐 Web' : '🏪 Kasir' }}
                            </div>
                        </td>
                        
                        <td style="padding: 12px 16px;">
                            <span v-if="trx.status === 'selesai'" style="background: #d3f9d8; color: #2b8a3e; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">Selesai</span>
                            <span v-else-if="trx.status === 'menunggu_pembayaran'" style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">Menunggu Bayar</span>
                            <span v-else-if="trx.status === 'menunggu_konfirmasi'" style="background: #cce5ff; color: #004085; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">Butuh Verifikasi</span>
                            <span v-else style="background: #f8d7da; color: #721c24; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">Dibatalkan</span>
                        </td>

                        <td style="padding: 12px 16px; font-weight: 600; color: #0b1c30;">Rp {{ Number(trx.total_harga).toLocaleString('id-ID') }}</td>
                        <td style="padding: 12px 16px; color: #3f484a; font-size: 0.8rem;">{{ new Date(trx.tanggal_transaksi).toLocaleDateString('id-ID') }}</td>
                        
                        <td style="padding: 12px 16px; text-align: center;">
                            <div class="flex items-center justify-center gap-3">
                                <button @click="lihatDetail(trx)" style="color: #0b1c30; font-size: 0.8rem; font-weight: 600; background: none; border: none; cursor: pointer;" class="hover:underline">
                                    Detail
                                </button>
                                
                                <template v-if="trx.status === 'menunggu_konfirmasi'">
                                    <button @click="terimaPesanan(trx.id)" style="color: #2b8a3e; font-size: 0.8rem; font-weight: 700; background: none; border: none; cursor: pointer;" class="hover:underline">
                                        ✓ Terima
                                    </button>
                                    <button @click="tolakPesanan(trx.id)" style="color: #ba1a1a; font-size: 0.8rem; font-weight: 700; background: none; border: none; cursor: pointer;" class="hover:underline">
                                        ✕ Tolak
                                    </button>
                                </template>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!transaksi || transaksi.length === 0">
                        <td colspan="5" style="padding: 40px; text-align: center; color: #90a4b4;">
                            Belum ada transaksi.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="showKasir" class="fixed inset-0 flex items-center justify-center z-50" style="background: rgba(11,28,48,0.5);">
            <div style="background: #fff; border-radius: 16px; padding: 24px; width: 100%; max-width: 520px; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #0b1c30; margin-bottom: 20px;">🧾 Transaksi Kasir Baru</h2>

                <div class="flex gap-2 mb-3">
                    <select
                        v-model="selectedItemId"
                        style="flex: 1; border: 1px solid #bfc8c9; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; color: #0b1c30; outline: none;"
                    >
                        <option value="" disabled>Pilih Item</option>
                        <option v-for="item in items" :key="item.id" :value="item.id">
                            {{ item.nama_item }} (Stok: {{ item.stok }})
                        </option>
                    </select>
                    <input
                        v-model.number="selectedQty"
                        type="number"
                        min="1"
                        style="width: 70px; border: 1px solid #bfc8c9; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; color: #0b1c30; outline: none; text-align: center;"
                    />
                    <button
                        @click="tambahKeKeranjang"
                        style="background: #004349; color: #fff; padding: 8px 16px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer;"
                        class="hover:opacity-90"
                    >
                        Tambah
                    </button>
                </div>

                <div style="border: 1px solid #e5eeff; border-radius: 8px; max-height: 180px; overflow-y: auto; margin-bottom: 16px;">
                    <div v-if="keranjang.length === 0" style="padding: 16px; text-align: center; color: #90a4b4; font-size: 0.875rem;">
                        Belum ada item di keranjang
                    </div>
                    <div
                        v-for="(k, index) in keranjang"
                        :key="index"
                        style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-top: 1px solid #e5eeff; font-size: 0.875rem;"
                    >
                        <div>
                            <p style="font-weight: 600; color: #0b1c30;">{{ k.nama_item }}</p>
                            <p style="color: #3f484a; font-size: 0.8rem;">{{ k.qty }} x Rp {{ k.harga_jual.toLocaleString('id-ID') }}</p>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-weight: 700; color: #004349;">Rp {{ (k.qty * k.harga_jual).toLocaleString('id-ID') }}</span>
                            <button
                                @click="hapusDariKeranjang(index)"
                                style="color: #ba1a1a; font-size: 0.75rem; font-weight: 600; background: none; border: none; cursor: pointer;"
                                class="hover:underline"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>

                <div style="border-top: 1px solid #e5eeff; padding-top: 14px; display: flex; flex-direction: column; gap: 10px; font-size: 0.875rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #3f484a;">Total Belanja</span>
                        <span style="font-weight: 700; color: #0b1c30;">Rp {{ totalBelanja.toLocaleString('id-ID') }}</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <label style="color: #3f484a;">Bayar Cash</label>
                        <input
                            v-model.number="bayar"
                            type="number"
                            placeholder="0"
                            style="width: 160px; border: 1px solid #bfc8c9; border-radius: 8px; padding: 7px 12px; font-size: 0.875rem; text-align: right; color: #0b1c30; outline: none;"
                        />
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #3f484a;">Kembalian</span>
                        <span :style="kembalian < 0 ? 'color: #ba1a1a; font-weight: 700;' : 'color: #004349; font-weight: 700;'">
                            Rp {{ kembalian.toLocaleString('id-ID') }}
                        </span>
                    </div>
                </div>

                <div class="flex justify-end gap-2" style="margin-top: 20px;">
                    <button
                        @click="showKasir = false"
                        style="padding: 8px 18px; border-radius: 8px; border: 1px solid #bfc8c9; font-size: 0.875rem; font-weight: 600; color: #3f484a; background: #fff; cursor: pointer;"
                        class="hover:bg-[#f8f9ff]"
                    >
                        Batal
                    </button>
                    <button
                        @click="simpanTransaksi"
                        :disabled="keranjang.length === 0 || kembalian < 0"
                        style="padding: 8px 18px; border-radius: 8px; background: #004349; color: #fff; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer;"
                        :style="(keranjang.length === 0 || kembalian < 0) ? 'opacity: 0.4; cursor: not-allowed;' : 'opacity: 1; cursor: pointer;'"
                        class="hover:opacity-90"
                    >
                        Selesaikan Kasir
                    </button>
                </div>
            </div>
        </div>

        <div v-if="showDetail && selectedTrx" class="fixed inset-0 flex items-center justify-center z-50" style="background: rgba(11,28,48,0.5);">
            <div style="background: #fff; border-radius: 16px; padding: 24px; width: 100%; max-width: 440px; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #0b1c30; margin-bottom: 20px;">Detail Transaksi</h2>
                <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.875rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #3f484a;">No. Invoice</span>
                        <span style="font-weight: 700; color: #004349; font-family: monospace;">{{ selectedTrx.nomor_invoice }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #3f484a;">Sumber</span>
                        <span style="font-weight: 600; color: #0b1c30;">{{ selectedTrx.sumber === 'online' ? 'Web Online' : 'Kasir Offline' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #3f484a;">Tanggal</span>
                        <span style="color: #0b1c30;">{{ new Date(selectedTrx.tanggal_transaksi).toLocaleDateString('id-ID') }}</span>
                    </div>

                    <div v-if="selectedTrx.detail_transaksi && selectedTrx.detail_transaksi.length > 0"
                        style="background: #f8f9ff; border-radius: 8px; padding: 12px; display: flex; flex-direction: column; gap: 6px; margin: 4px 0;">
                        <div v-for="d in selectedTrx.detail_transaksi" :key="d.id"
                            style="display: flex; justify-content: space-between;">
                            <span style="color: #3f484a;">{{ d.nama_item }} x{{ d.qty }}</span>
                            <span style="font-weight: 600; color: #0b1c30;">Rp {{ Number(d.subtotal).toLocaleString('id-ID') }}</span>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #e5eeff; padding-top: 10px; display: flex; flex-direction: column; gap: 8px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #3f484a;">Total Harga</span>
                            <span style="color: #0b1c30;">Rp {{ Number(selectedTrx.total_harga).toLocaleString('id-ID') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #3f484a;">Pembayaran (Cash/Transfer)</span>
                            <span style="color: #0b1c30;">Rp {{ Number(selectedTrx.bayar).toLocaleString('id-ID') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;" v-if="selectedTrx.sumber === 'offline'">
                            <span style="font-weight: 700; color: #0b1c30;">Kembalian</span>
                            <span style="font-weight: 700; color: #004349;">Rp {{ Number(selectedTrx.kembalian).toLocaleString('id-ID') }}</span>
                        </div>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 20px;">
                    <a
                        :href="`/struk/${selectedTrx.id}`"
                        target="_blank"
                        style="padding: 8px 18px; border-radius: 8px; background: #004349; color: #fff; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none;"
                        class="hover:opacity-90"
                    >
                        🖨️ Cetak Struk
                    </a>
                    <button
                        @click="showDetail = false"
                        style="padding: 8px 18px; border-radius: 8px; border: 1px solid #bfc8c9; font-size: 0.875rem; font-weight: 600; color: #3f484a; background: #fff; cursor: pointer;"
                        class="hover:bg-[#f8f9ff]"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>