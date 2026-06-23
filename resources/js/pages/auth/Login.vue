<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { register } from '@/routes';
import { store } from '@/routes/login';

defineOptions({
    layout: {
        title: 'Selamat Datang',
        description: 'Masuk ke sistem manajemen Toko Ikan Koi',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <Head title="Login - Toko Ikan Koi" />

    <div class="relative min-h-screen flex items-center justify-center p-6 overflow-hidden bg-[#f8f9ff]">

        <!-- Animated background ripples -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="zen-ripple w-[400px] h-[400px] -top-20 -left-20"></div>
            <div class="zen-ripple w-[600px] h-[600px] top-1/2 left-3/4" style="animation-delay: 2s;"></div>
            <div class="zen-ripple w-[300px] h-[300px] bottom-10 left-1/4" style="animation-delay: 4s;"></div>
            <div class="absolute top-20 right-[15%] opacity-10 floating-koi">
                <span class="material-symbols-outlined" style="font-size:120px; color:#004349;">sailing</span>
            </div>
            <div class="absolute bottom-40 left-[10%] opacity-10 floating-koi" style="animation-delay: -3s;">
                <span class="material-symbols-outlined" style="font-size:80px; color:#934a29;">water_drop</span>
            </div>
        </div>

        <!-- Card -->
        <main class="relative z-10 w-full max-w-[440px]">
            <div class="bg-white rounded-xl border border-[#bfc8c9]/30 p-8 md:p-12 zen-card">

                <!-- Brand -->
                <div class="flex flex-col items-center mb-10">
                    <div class="w-16 h-16 rounded-full overflow-hidden mb-4 shadow-sm">
                        <img src="/images/logo_toko_koi.jpg" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;" />
                    </div>
                    <h1 class="text-2xl font-semibold text-[#004349] tracking-tight" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Toko Ikan Koi
                    </h1>
                    <p class="text-sm text-[#3f484a] mt-1" style="font-family: 'Inter', sans-serif;">
                        Sistem Manajemen Toko
                    </p>
                </div>

                <!-- Status -->
                <div v-if="status" class="mb-6 p-3 rounded-lg bg-[#abeef6]/30 border border-[#004349]/20 text-center text-sm font-medium text-[#004349]">
                    {{ status }}
                </div>

                <!-- Form -->
                <Form
                    v-bind="store.form()"
                    :reset-on-success="['password']"
                    v-slot="{ errors, processing }"
                    class="flex flex-col gap-6"
                >
                    <div class="grid gap-6">

                        <!-- Username -->
                        <div class="grid gap-2">
                            <Label for="username" class="text-sm font-medium text-[#3f484a]">Username</Label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#6f797a] transition-colors group-focus-within:text-[#004349]" style="font-size:20px;">person</span>
                                <Input
                                    id="username"
                                    type="text"
                                    name="username"
                                    required
                                    autofocus
                                    :tabindex="1"
                                    autocomplete="username"
                                    placeholder="Masukkan username anda"
                                    class="pl-10 border-[#bfc8c9] focus:border-[#004349] focus:ring-[#004349]/20"
                                />
                            </div>
                            <InputError :message="errors.username" />
                        </div>

                        <!-- Password -->
                        <div class="grid gap-2">
                            <Label for="password" class="text-sm font-medium text-[#3f484a]">Password</Label>
                            <div class="relative group">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#6f797a] transition-colors group-focus-within:text-[#004349] z-10" style="font-size:20px;">lock</span>
                                <PasswordInput
                                    id="password"
                                    name="password"
                                    required
                                    :tabindex="2"
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                    class="pl-10 border-[#bfc8c9] focus:border-[#004349] focus:ring-[#004349]/20"
                                />
                            </div>
                            <InputError :message="errors.password" />
                        </div>

                        <!-- Ingat Saya -->
                        <div class="flex items-center justify-between">
                            <Label for="remember" class="flex items-center space-x-3 cursor-pointer">
                                <Checkbox id="remember" name="remember" :tabindex="3" />
                                <span class="text-sm text-[#3f484a]">Ingat saya</span>
                            </Label>
                        </div>

                        <!-- Submit -->
                        <Button
                            type="submit"
                            class="mt-2 w-full py-4 bg-[#004349] hover:bg-[#0d5c63] text-white font-semibold text-base flex items-center justify-center gap-2"
                            :tabindex="4"
                            :disabled="processing"
                        >
                            <Spinner v-if="processing" />
                            <template v-else>
                                Masuk
                                <span class="material-symbols-outlined" style="font-size:20px;">login</span>
                            </template>
                        </Button>
                    </div>
                </Form>

                <!-- Register link -->
                <div class="mt-10 pt-8 border-t border-[#bfc8c9]/30 flex flex-col items-center gap-4">
                    <p class="text-sm text-[#3f484a]">Belum memiliki akun?</p>
                    <div class="flex gap-4 w-full">
                        <TextLink
                            :href="register()"
                            :tabindex="5"
                            class="flex-1 text-center py-3 border border-[#004349] text-[#004349] text-sm font-medium rounded-lg hover:bg-[#abeef6]/30 transition-colors"
                        >
                            Daftar
                        </TextLink>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-8 text-center">
                <p class="text-xs text-[#6f797a]">
                    © 2026 Toko Ikan Koi.<br>
                    <span class="opacity-70 italic">Kualitas terbaik untuk ikan koi pilihanmu.</span>
                </p>
            </footer>
        </main>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    font-family: 'Material Symbols Outlined';
}

.zen-card {
    box-shadow: 0 10px 25px -5px rgba(13, 92, 99, 0.08), 0 1px 3px rgba(0,0,0,0.04);
}

.zen-ripple {
    position: absolute;
    border: 1px solid rgba(13, 92, 99, 0.1);
    border-radius: 50%;
    animation: ripple 6s infinite ease-out;
}

@keyframes ripple {
    0%   { transform: scale(0.5); opacity: 0; }
    50%  { opacity: 1; }
    100% { transform: scale(2.5); opacity: 0; }
}

.floating-koi {
    animation: float 8s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0); }
    33%       { transform: translate(15px, -10px); }
    66%       { transform: translate(-10px, 15px); }
}
</style>