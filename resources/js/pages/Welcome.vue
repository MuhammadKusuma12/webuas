<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { store } from '@/routes/login/index';
</script>

<template>
    <Head title="Toko Ikan Koi" />
    <div class="min-h-screen bg-white flex flex-col">

        <!-- Navbar -->
        <nav class="flex items-center justify-between px-8 py-4 border-b">
            <div class="flex items-center gap-2 font-bold text-lg">
                🐟 Toko Ikan Koi
            </div>
            <Link :href="store.url()" class="bg-black text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
                Masuk
            </Link>
        </nav>

        <!-- Hero -->
        <div class="flex flex-col items-center justify-center flex-1 text-center px-6 py-24 gap-6">
            <div class="text-6xl">🐠</div>
            <h1 class="text-4xl font-bold">Sistem Manajemen Toko Ikan Koi</h1>
            <p class="text-muted-foreground text-lg max-w-md">
                Kelola stok, transaksi, dan laporan mutasi stok ikan koi Anda dengan mudah dan efisien.
            </p>
            <Link :href="store.url()" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 font-medium">
                Mulai Sekarang
            </Link>
        </div>

        <!-- Fitur -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-8 pb-16 max-w-4xl mx-auto w-full">
            <div class="border rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">📦</div>
                <h3 class="font-semibold mb-1">Manajemen Stok</h3>
                <p class="text-sm text-muted-foreground">Pantau stok ikan koi secara real-time dan dapatkan notifikasi stok menipis.</p>
            </div>
            <div class="border rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">🧾</div>
                <h3 class="font-semibold mb-1">Sistem Kasir</h3>
                <p class="text-sm text-muted-foreground">Proses transaksi penjualan dengan cepat dan cetak invoice otomatis.</p>
            </div>
            <div class="border rounded-xl p-6 text-center">
                <div class="text-3xl mb-3">📊</div>
                <h3 class="font-semibold mb-1">Laporan Mutasi</h3>
                <p class="text-sm text-muted-foreground">Lihat riwayat perubahan stok masuk dan keluar secara lengkap.</p>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center text-sm text-muted-foreground py-4 border-t">
            © 2026 Toko Ikan Koi. All rights reserved.
        </footer>

    </div>
</template>