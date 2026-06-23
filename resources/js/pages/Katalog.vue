<script lang="ts">
export default {
    layout: null,
}
</script>

<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Ikan {
    id: number;
    kode: string;
    nama_item: string;
    harga_jual: number;
    stok: number;
}

defineProps<{
    katalogIkan: Ikan[];
}>();

const page = usePage();
const showLoginModal = ref(false);
const showImageModal = ref(false);
const selectedImage = ref('');

function getImageSrc(namaItem: string): string {
    if (namaItem.includes('Premium')) return '/images/products/pakan-premium.jpg';
    if (namaItem.includes('Growth')) return '/images/products/pakan-growth.png';
    if (namaItem.includes('Jaring')) return '/images/products/jaring-koi.jpg';
    if (namaItem.includes('Aerator')) return '/images/products/aerator.jpeg';
    if (namaItem.includes('Vitamin')) return '/images/products/vitamin-koi.jpg';
    if (namaItem.includes('Jamur')) return '/images/products/obat-jamur.jpg';
    if (namaItem.includes('Kohaku')) return '/images/products/koi-kohaku.jpg';
    if (namaItem.includes('Showa')) return '/images/products/koi-showa.jpg';
    if (namaItem.includes('Ogon')) return '/images/products/koi-ogon.jpg';
    if (namaItem.includes('Bekko')) return '/images/products/koi-bekko.jpg';
    if (namaItem.includes('Tanco')) return '/images/products/koi-tanco.jpg';
    return '/images/products/pakan-premium.jpg';
}

function openImage(src: string) {
    selectedImage.value = src;
    showImageModal.value = true;
}

function handleBeli(itemId: number) {
    const user = page.props.auth.user;
    if (!user) {
        showLoginModal.value = true;
        return;
    }
    if (user.role !== 'pembeli') {
        router.visit('/dashboard');
        return;
    }
    router.visit(`/checkout/${itemId}`);
}

function formatRupiah(num: number) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}
</script>

<template>
    <Head title="Toko Ikan Koi" />

    <div class="min-h-screen flex flex-col" style="background: #f8f9ff; font-family: 'Inter', sans-serif; color: #0b1c30;">

        <!-- Navbar -->
        <nav style="background: #ffffff; border-bottom: 1px solid #e5eeff; box-shadow: 0 1px 3px rgba(0,0,0,0.04);" class="flex items-center justify-between px-8 py-4">
            <div class="flex items-center gap-3">
                <div style="width: 36px; height: 36px; border-radius: 10px; overflow: hidden;">
                    <img src="/images/logo_toko_koi.jpg" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;" />
                </div>
                <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 1.05rem; color: #004349;">Toko Ikan Koi</span>
            </div>

            <div class="flex items-center gap-3">
                <template v-if="!$page.props.auth.user">
                    <Link href="/login" style="color: #3f484a; font-size: 0.875rem; font-weight: 500;" class="hover:opacity-70">Masuk</Link>
                    <Link href="/register" style="background: #004349; color: #fff; padding: 8px 18px; border-radius: 8px; font-size: 0.875rem; font-weight: 600;" class="hover:opacity-90 transition-opacity">Daftar</Link>
                </template>
                <template v-else>
                    <span style="font-size: 0.875rem; font-weight: 500; color: #3f484a;">
                        Halo, {{ $page.props.auth.user.nama || $page.props.auth.user.username }}!
                    </span>
                    <Link v-if="$page.props.auth.user.role !== 'pembeli'" href="/dashboard" style="background: #004349; color: #fff; padding: 8px 16px; border-radius: 8px; font-size: 0.875rem; font-weight: 600;" class="hover:opacity-90 transition-opacity">Dashboard</Link>
                    <Link v-if="$page.props.auth.user.role === 'pembeli'" href="/pesanan-saya" style="color: #004349; font-size: 0.875rem; font-weight: 500; border: 1px solid #bfc8c9; padding: 7px 14px; border-radius: 8px;" class="hover:bg-[#e5eeff] transition-colors">Pesanan Saya</Link>
                    <Link href="/logout" method="post" as="button" style="color: #ba1a1a; font-size: 0.875rem; font-weight: 500; border: 1px solid #ffdad6; padding: 7px 14px; border-radius: 8px;" class="hover:bg-[#ffdad6] transition-colors">Keluar</Link>
                </template>
            </div>
        </nav>

        <!-- Hero -->
        <div class="flex flex-col items-center justify-center text-center px-6 pt-14 pb-10 gap-3">
            <div style="width: 72px; height: 72px; border-radius: 50%; overflow: hidden;">
                <img src="/images/logo_toko_koi.jpg" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>
            <h1 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2rem; font-weight: 700; color: #0b1c30; letter-spacing: -0.02em;">Katalog Produk Pilihan</h1>
            <p style="color: #3f484a; font-size: 0.95rem; max-width: 400px; line-height: 1.6;">Temukan produk dan ikan koi berkualitas terbaik langsung dari kolam kami.</p>
        </div>

        <!-- Grid Produk -->
        <div class="px-8 pb-20 max-w-6xl mx-auto w-full flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <div
                    v-for="ikan in katalogIkan"
                    :key="ikan.id"
                    class="flex flex-col hover:shadow-lg transition-shadow"
                    style="background: #ffffff; border: 1px solid #e5eeff; border-radius: 16px; overflow: hidden;"
                >
                    <!-- Gambar -->
                    <div
                        style="position: relative; height: 160px; overflow: hidden; background: #dce9ff; cursor: zoom-in;"
                        @click="openImage(getImageSrc(ikan.nama_item))"
                    >
                        <img
                            :src="getImageSrc(ikan.nama_item)"
                            :alt="ikan.nama_item"
                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                            class="hover:scale-105"
                        />
                        <!-- Overlay zoom hint -->
                        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0); display: flex; align-items: center; justify-content: center; opacity: 0; transition: all 0.2s;" class="hover:!bg-black/20 hover:!opacity-100">
                            <span class="material-symbols-outlined" style="font-size: 32px; color: white; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));">zoom_in</span>
                        </div>
                        <!-- Badge stok -->
                        <span v-if="ikan.stok <= 3 && ikan.stok > 0" style="position: absolute; top: 10px; right: 10px; background: #ba1a1a; color: #fff; font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 999px;">Stok Terbatas</span>
                        <span v-if="ikan.stok === 0" style="position: absolute; top: 10px; right: 10px; background: #6f797a; color: #fff; font-size: 0.65rem; font-weight: 700; padding: 2px 8px; border-radius: 999px;">Habis</span>
                    </div>

                    <!-- Konten Card -->
                    <div class="flex flex-col flex-1 p-4 gap-3">
                        <div>
                            <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 0.95rem; color: #0b1c30; margin-bottom: 4px; line-height: 1.3;">{{ ikan.nama_item }}</h3>
                            <div class="flex items-center gap-1.5">
                                <span style="font-size: 0.7rem; font-weight: 500; color: #6f797a;">Stok:</span>
                                <span :style="ikan.stok === 0 ? 'color: #ba1a1a; font-weight: 700; font-size: 0.75rem;' : ikan.stok <= 3 ? 'color: #934a29; font-weight: 700; font-size: 0.75rem;' : 'color: #004349; font-weight: 700; font-size: 0.75rem;'">
                                    {{ ikan.stok }} ekor
                                </span>
                            </div>
                        </div>
                        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.15rem; font-weight: 800; color: #004349;">
                            {{ formatRupiah(ikan.harga_jual) }}
                        </div>
                        <div class="mt-auto">
                            <button
                                v-if="!$page.props.auth.user || $page.props.auth.user.role === 'pembeli'"
                                @click="handleBeli(ikan.id)"
                                :disabled="ikan.stok === 0"
                                class="w-full transition-opacity hover:opacity-90"
                                :style="ikan.stok === 0 ? 'background: #e5eeff; color: #90a4b4; cursor: not-allowed; padding: 9px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: none;' : 'background: #004349; color: #fff; cursor: pointer; padding: 9px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: none;'"
                            >
                                {{ ikan.stok === 0 ? 'Stok Habis' : 'Beli Sekarang' }}
                            </button>
                            <div v-else style="width: 100%; background: #e5eeff; color: #004349; padding: 9px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-align: center;">
                                Kelola via Dashboard
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kosong -->
            <div v-if="katalogIkan.length === 0" class="text-center py-20">
                <div class="text-5xl mb-4">🪣</div>
                <p style="color: #3f484a; font-size: 1rem; font-weight: 500;">Stok produk sedang kosong saat ini.</p>
            </div>
        </div>

        <!-- Footer -->
        <footer style="border-top: 1px solid #e5eeff; padding: 20px; text-align: center; font-size: 0.8rem; color: #6f797a;">
            © 2026 Toko Ikan Koi. All rights reserved.
        </footer>

        <!-- Modal Login -->
        <div v-if="showLoginModal" class="fixed inset-0 flex items-center justify-center z-50 px-4" style="background: rgba(11,28,48,0.5);">
            <div style="background: #fff; border-radius: 20px; padding: 32px; max-width: 360px; width: 100%; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
                <div class="text-4xl mb-3">🔐</div>
                <h3 style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 1.1rem; color: #0b1c30; margin-bottom: 8px;">Login Diperlukan</h3>
                <p style="color: #3f484a; font-size: 0.875rem; margin-bottom: 20px; line-height: 1.5;">Silakan masuk atau daftar terlebih dahulu untuk membeli produk ini.</p>
                <div class="flex gap-2">
                    <Link href="/login" style="flex: 1; background: #004349; color: #fff; padding: 10px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-align: center; display: block;">Masuk</Link>
                    <Link href="/register" style="flex: 1; border: 1px solid #bfc8c9; color: #0b1c30; padding: 10px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-align: center; display: block;">Daftar</Link>
                </div>
                <button @click="showLoginModal = false" style="margin-top: 12px; font-size: 0.75rem; color: #90a4b4;" class="hover:opacity-70">Batal</button>
            </div>
        </div>

        <!-- Modal Preview Gambar -->
        <div v-if="showImageModal" class="fixed inset-0 flex items-center justify-center z-50 px-4" style="background: rgba(0,0,0,0.85);" @click="showImageModal = false">
            <div style="position: relative; max-width: 600px; width: 100%;" @click.stop>
                <img :src="selectedImage" style="width: 100%; max-height: 80vh; object-fit: contain; border-radius: 12px;" />
                <button
                    @click="showImageModal = false"
                    style="position: absolute; top: -16px; right: -16px; width: 36px; height: 36px; background: #fff; border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"
                >
                    <span class="material-symbols-outlined" style="font-size: 20px; color: #0b1c30;">close</span>
                </button>
            </div>
        </div>

    </div>
</template>