<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: {
        title: 'Buat Akun Pembeli',
        description: 'Daftar untuk mulai berbelanja ikan koi pilihan',
    },
});
</script>

<template>
    <Head title="Daftar - Toko Ikan Koi" />

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-5">
            <div class="grid gap-2">
                <Label for="nama" class="text-sm font-semibold text-[#3f484a]">Nama Lengkap</Label>
                <Input
                    id="nama"
                    type="text"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="name"
                    name="nama"
                    placeholder="Nama lengkap kamu"
                    class="border-[#bfc8c9] rounded-lg focus:border-[#004349] focus:ring-[#004349]/20"
                />
                <InputError :message="errors.nama" />
            </div>

            <div class="grid gap-2">
                <Label for="username" class="text-sm font-semibold text-[#3f484a]">Username</Label>
                <Input
                    id="username"
                    type="text"
                    required
                    :tabindex="2"
                    autocomplete="username"
                    name="username"
                    placeholder="Pilih username unikmu"
                    class="border-[#bfc8c9] rounded-lg focus:border-[#004349] focus:ring-[#004349]/20"
                />
                <InputError :message="errors.username" />
            </div>

            <div class="grid gap-2">
                <Label for="password" class="text-sm font-semibold text-[#3f484a]">Password</Label>
                <PasswordInput
                    id="password"
                    required
                    :tabindex="3"
                    autocomplete="new-password"
                    name="password"
                    placeholder="Buat password yang kuat"
                    :passwordrules="passwordRules"
                    class="border-[#bfc8c9] rounded-lg focus:border-[#004349] focus:ring-[#004349]/20"
                />
                <InputError :message="errors.password" />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full bg-[#004349] hover:bg-[#0d5c63] text-white rounded-lg font-semibold text-sm py-4 transition-colors"
                tabindex="4"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                Buat Akun
            </Button>
        </div>

        <div class="text-center text-sm text-[#3f484a]">
            Sudah punya akun?
            <TextLink :href="login()" class="text-[#004349] font-semibold underline" :tabindex="5">
                Masuk di sini
            </TextLink>
        </div>
    </Form>
</template>