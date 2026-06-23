<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    item: {
        id: number
        kode: string
        nama_item: string
        harga_jual: number
        stok: number
    };
}>();

const form = useForm({
    qty: 1,
});

function submit() {
    form.post(`/checkout/${props.item.id}`);
}

function formatRupiah(num: number) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function getImageName(namaItem: string): string {
    if (namaItem.includes('Premium')) return 'pakan-premium.jpg';
    if (namaItem.includes('Growth')) return 'pakan-growth.png';
    if (namaItem.includes('Jaring')) return 'jaring-koi.jpg';
    if (namaItem.includes('Aerator')) return 'aerator.jpg';
    if (namaItem.includes('Vitamin')) return 'vitamin-koi.jpg';
    if (namaItem.includes('Jamur')) return 'obat-jamur.jpg';
    return 'pakan-premium.jpg';
}
</script>

<template>
    <Head :title="`Beli ${item.nama_item}`" />

    <div class="min-h-screen flex items-center justify-center px-6 py-12"
        style="background: #f8f9ff; font-family: 'Inter', sans-serif;">

        <div style="width: 100%; max-width: 420px;">

            <!-- Back link -->
            <a href="/"
                style="display: inline-flex; align-items: center; gap: 6px; color: #3f484a; font-size: 0.875rem; font-weight: 500; text-decoration: none; margin-bottom: 24px;"
                class="hover:opacity-70">
                ← Kembali ke Katalog
            </a>

            <!-- Card -->
            <div style="background: #fff; border: 1px solid #e5eeff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">

                <!-- Gambar produk -->
                <div style="position: relative; height: 200px; overflow: hidden; background: #dce9ff;">
                    <img
                        :src="`/images/products/${getImageName(item.nama_item)}`"
                        :alt="item.nama_item"
                        style="width: 100%; height: 100%; object-fit: cover;"
                    />
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(11,28,48,0.6) 0%, transparent 50%);"></div>
                    <div style="position: absolute; bottom: 16px; left: 16px; right: 16px;">
                        <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.2rem; font-weight: 700; color: #fff; margin-bottom: 2px;">
                            {{ item.nama_item }}
                        </h1>
                        <p style="font-size: 0.8rem; color: rgba(255,255,255,0.8);">
                            Sisa stok: <span style="font-weight: 700;">{{ item.stok }} ekor</span>
                        </p>
                    </div>
                </div>

                <!-- Form -->
                <div style="padding: 24px;" class="flex flex-col gap-4">

                    <!-- Harga -->
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; font-weight: 800; color: #004349;">
                        {{ formatRupiah(item.harga_jual) }}
                    </div>

                    <form @submit.prevent="submit" class="flex flex-col gap-4">

                        <!-- Input Jumlah -->
                        <div>
                            <label for="qty"
                                style="font-size: 0.85rem; font-weight: 600; color: #3f484a; display: block; margin-bottom: 6px;">
                                Jumlah yang dibeli
                            </label>
                            <input
                                id="qty"
                                type="number"
                                v-model.number="form.qty"
                                min="1"
                                :max="item.stok"
                                class="focus:outline-none focus:ring-2 focus:ring-[#004349]/20 focus:border-[#004349]"
                                style="width: 100%; border: 1px solid #bfc8c9; border-radius: 8px; padding: 10px 14px; font-size: 0.95rem; color: #0b1c30; box-sizing: border-box; transition: border-color 0.2s;"
                            />
                            <p v-if="form.errors.qty" style="color: #ba1a1a; font-size: 0.75rem; margin-top: 4px;">
                                {{ form.errors.qty }}
                            </p>
                        </div>

                        <!-- Subtotal -->
                        <div style="background: #f8f9ff; border: 1px solid #e5eeff; border-radius: 8px; padding: 14px; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.875rem; color: #3f484a;">Subtotal</span>
                            <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.1rem; font-weight: 800; color: #004349;">
                                {{ formatRupiah(item.harga_jual * (form.qty || 0)) }}
                            </span>
                        </div>

                        <!-- Tombol Submit -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            :style="form.processing
                                ? 'opacity: 0.5; cursor: not-allowed; width: 100%; background: #004349; color: #fff; padding: 12px; border-radius: 8px; font-size: 0.95rem; font-weight: 700; border: none;'
                                : 'opacity: 1; width: 100%; background: #004349; color: #fff; padding: 12px; border-radius: 8px; font-size: 0.95rem; font-weight: 700; border: none; cursor: pointer;'"
                            class="hover:opacity-90 transition-opacity"
                        >
                            {{ form.processing ? 'Memproses...' : 'Konfirmasi Beli' }}
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>