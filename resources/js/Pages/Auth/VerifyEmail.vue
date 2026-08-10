<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/ui/Button.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { EnvelopeIcon } from '@heroicons/vue/24/outline';

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                <EnvelopeIcon class="h-7 w-7" />
            </div>
            <h1 class="mt-5 text-2xl font-extrabold text-navy-950">Verify your email</h1>
            <p class="mx-auto mt-2 max-w-sm text-sm leading-relaxed text-slate-500">
                Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent you.
            </p>
        </div>

        <div class="mt-8 space-y-4">
            <form @submit.prevent="submit">
                <Button type="submit" class="w-full" size="lg" :loading="form.processing">Resend verification email</Button>
            </form>
            <div class="text-center text-sm">
                <Link :href="route('profile.edit')" class="font-semibold text-primary-700 hover:text-primary-800">← Back to profile</Link>
            </div>
        </div>
    </GuestLayout>
</template>
