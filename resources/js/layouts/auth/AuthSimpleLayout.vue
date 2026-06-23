<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';
defineProps<{
    title?: string;
    description?: string;
}>();
</script>
<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10"
    >
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-2 font-medium"
                    >
                        <div class="mb-1 h-9 w-9 rounded-md overflow-hidden">
                            <img src="/images/logo_toko_koi.jpg" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;" />
                        </div>
                        <span class="text-lg font-bold">Toko Ikan Koi</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>