<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import Label from '@/Components/ui/Label.vue';
import FieldError from '@/Components/ui/FieldError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: { type: String, default: '' },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-navy-950">Reset your password</h1>
            <p class="mt-1.5 text-sm text-slate-500">
                Forgot your password? No problem. Enter your email and we'll send you a reset link.
            </p>
        </div>

        <div v-if="status" class="mb-4 flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ status }}
        </div>

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <Label for="email" value="Email address" required />
                <Input id="email" type="email" v-model="form.email" placeholder="you@example.com" :error="!!form.errors.email" autofocus />
                <FieldError :message="form.errors.email" />
            </div>

            <Button type="submit" class="w-full" size="lg" :loading="form.processing">Email password reset link</Button>

            <p class="text-center text-sm text-slate-500">
                Remembered it?
                <Link :href="route('login')" class="font-semibold text-primary-700 hover:text-primary-800">Log in</Link>
            </p>
        </form>
    </GuestLayout>
</template>
