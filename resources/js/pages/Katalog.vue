<script lang="ts">
export default {
    layout: null,
}
</script>

<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

// 1. Membuat Cetak Biru (Interface) untuk data ikan
interface Ikan {
    id: number;
    kode: string;
    nama_item: string;
    harga_jual: number;
    stok: number;
}

// 2. Menerapkan Cetak Biru ke dalam defineProps
defineProps<{
    katalogIkan: Ikan[];
}>();

const page = usePage();
const showLoginModal = ref(false);

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
</script>

<template>
    <Head title="Toko Ikan Koi" />

    <div class="min-h-screen flex flex-col" style="background: #f8f9ff; font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; color: #0b1c30;">

        <nav style="background: #ffffff; border-bottom: 1px solid #bfc8c9;" class="flex items-center justify-between px-8 py-4">
            <div class="flex items-center gap-3">
                <div style="background: #004349; border-radius: 10px; width: 36px; height: 36px;" class="flex items-center justify-center text-white text-lg">
                    🎏
                </div>
                <span style="font-weight: 700; font-size: 1.1rem; color: #004349;">Toko Ikan Koi</span>
            </div>

            <div class="flex items-center gap-3">
                <template v-if="!$page.props.auth.user">
                    <Link href="/login" style="color: #3f484a; font-size: 0.875rem; font-weight: 500;" class="hover:opacity-70">
                        Masuk
                    </Link>
                    <Link href="/register" style="background: #004349; color: #fff; padding: 8px 18px; border-radius: 8px; font-size: 0.875rem; font-weight: 600;" class="hover:opacity-90 transition-opacity">
                        Daftar
                    </Link>
                </template>

                <template v-else>
                    <span style="font-size: 0.875rem; font-weight: 500; color: #3f484a; margin-right: 8px;">
                        Halo, {{ $page.props.auth.user.nama || $page.props.auth.user.username }}!
                    </span>

                    <Link
                        v-if="$page.props.auth.user.role !== 'pembeli'"
                        href="/dashboard"
                        style="background: #004349; color: #fff; padding: 8px 16px; border-radius: 8px; font-size: 0.875rem; font-weight: 600;"
                        class="hover:opacity-90 transition-opacity"
                    >
                        Dashboard
                    </Link>

                    <Link
                        v-if="$page.props.auth.user.role === 'pembeli'"
                        href="/pesanan-saya"
                        style="color: #004349; font-size: 0.875rem; font-weight: 500; border: 1px solid #bfc8c9; padding: 7px 14px; border-radius: 8px;"
                        class="hover:opacity-70 transition-opacity"
                    >
                        Pesanan Saya
                    </Link>

                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        style="color: #ba1a1a; font-size: 0.875rem; font-weight: 500; border: 1px solid #ffdad6; padding: 7px 14px; border-radius: 8px;"
                        class="hover:opacity-80 transition-opacity"
                    >
                        Keluar
                    </Link>
                </template>
            </div>
        </nav>

        <div class="flex flex-col items-center justify-center text-center px-6 pt-16 pb-10 gap-4">
            <div style="background: #dce9ff; border-radius: 50%; width: 72px; height: 72px;" class="flex items-center justify-center text-4xl mb-2">
                🐠
            </div>
            <h1 style="font-size: 2.25rem; font-weight: 800; color: #0b1c30; letter-spacing: -0.02em;">
                Katalog Ikan Koi Pilihan
            </h1>
            <p style="color: #3f484a; font-size: 1rem; max-width: 420px; line-height: 1.6;">
                Pilih dan temukan ikan koi impianmu dengan kualitas terbaik langsung dari kolam kami.
            </p>
        </div>

        <div class="px-8 pb-20 max-w-6xl mx-auto w-full flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">

                <div
                    v-for="ikan in katalogIkan"
                    :key="ikan.id"
                    style="background: #ffffff; border: 1px solid #bfc8c9; border-radius: 16px; overflow: hidden; transition: box-shadow 0.2s;"
                    class="flex flex-col hover:shadow-lg"
                >
                    <div style="background: linear-gradient(135deg, #dce9ff 0%, #e5eeff 100%); padding: 28px 0;" class="flex items-center justify-center text-5xl">
                        🎏
                    </div>

                    <div class="flex flex-col flex-1 p-4 gap-3">
                        <div>
                            <h3 style="font-weight: 700; font-size: 0.95rem; color: #0b1c30; margin-bottom: 2px;">{{ ikan.nama_item }}</h3>
                            <p style="font-size: 0.75rem; color: #3f484a;">
                                Sisa stok:
                                <span :style="ikan.stok <= 3 ? 'color: #ba1a1a; font-weight: 600;' : 'color: #004349; font-weight: 600;'">
                                    {{ ikan.stok }} ekor
                                </span>
                            </p>
                        </div>

                        <div style="font-size: 1.2rem; font-weight: 800; color: #004349;">
                            Rp {{ ikan.harga_jual.toLocaleString('id-ID') }}
                        </div>

                        <div class="mt-auto">
                            <button
                                v-if="!$page.props.auth.user || $page.props.auth.user.role === 'pembeli'"
                                @click="handleBeli(ikan.id)"
                                :disabled="ikan.stok === 0"
                                :style="ikan.stok === 0
                                    ? 'background: #e5eeff; color: #90a4b4; cursor: not-allowed;'
                                    : 'background: #004349; color: #fff; cursor: pointer;'"
                                style="width: 100%; padding: 9px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: none; transition: opacity 0.2s;"
                                class="hover:opacity-90"
                            >
                                {{ ikan.stok === 0 ? 'Stok Habis' : 'Beli Sekarang' }}
                            </button>

                            <div
                                v-else
                                style="width: 100%; background: #e5eeff; color: #004349; padding: 9px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-align: center;"
                            >
                                ⚙️ Kelola via Dashboard
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div v-if="katalogIkan.length === 0" class="text-center py-16">
                <div class="text-5xl mb-4">🪣</div>
                <p style="color: #3f484a; font-size: 1rem; font-weight: 500;">Mohon maaf, stok ikan sedang kosong saat ini.</p>
            </div>
        </div>

        <footer style="border-top: 1px solid #bfc8c9; padding: 20px; text-align: center; font-size: 0.8rem; color: #3f484a;">
            © 2026 Toko Ikan Koi. All rights reserved.
        </footer>

        <div v-if="showLoginModal" class="fixed inset-0 flex items-center justify-center z-50 px-4" style="background: rgba(11,28,48,0.5);">
            <div style="background: #fff; border-radius: 20px; padding: 28px; max-width: 360px; width: 100%; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
                <div class="text-4xl mb-3">🔐</div>
                <h3 style="font-weight: 700; font-size: 1.1rem; color: #0b1c30; margin-bottom: 8px;">Login Diperlukan</h3>
                <p style="color: #3f484a; font-size: 0.875rem; margin-bottom: 20px; line-height: 1.5;">
                    Silakan masuk atau daftar dulu untuk membeli ikan koi ini.
                </p>
                <div class="flex gap-2">
                    <Link href="/login" style="flex: 1; background: #004349; color: #fff; padding: 10px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-align: center; display: block;">
                        Masuk
                    </Link>
                    <Link href="/register" style="flex: 1; border: 1px solid #bfc8c9; color: #0b1c30; padding: 10px; border-radius: 8px; font-size: 0.875rem; font-weight: 600; text-align: center; display: block;">
                        Daftar
                    </Link>
                </div>
                <button @click="showLoginModal = false" style="margin-top: 12px; font-size: 0.75rem; color: #90a4b4;" class="hover:opacity-70">
                    Batal
                </button>
            </div>
        </div>

    </div>
</template>