<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import Label from '@/Components/ui/Label.vue';
import FieldError from '@/Components/ui/FieldError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean, default: false },
    status: { type: String, default: '' },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-navy-950">Welcome back</h1>
            <p class="mt-1.5 text-sm text-slate-500">Log in to manage your appointments and health care.</p>
        </div>

        <div v-if="status" class="mb-4 flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ status }}
        </div>

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <Label for="email" value="Email address" required />
                <Input id="email" type="email" v-model="form.email" placeholder="you@example.com" :error="!!form.errors.email" autocomplete="username" autofocus />
                <FieldError :message="form.errors.email" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <Label for="password" value="Password" required />
                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs font-semibold text-primary-700 hover:text-primary-800">
                        Forgot password?
                    </Link>
                </div>
                <Input id="password" type="password" v-model="form.password" placeholder="••••••••" :error="!!form.errors.password" autocomplete="current-password" />
                <FieldError :message="form.errors.password" />
            </div>

            <label class="flex cursor-pointer items-center gap-2.5">
                <input
                    v-model="form.remember"
                    type="checkbox"
                    class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500"
                />
                <span class="text-sm text-slate-600">Remember me</span>
            </label>

            <Button type="submit" class="w-full" size="lg" :loading="form.processing">Log in</Button>

            <p class="text-center text-sm text-slate-500">
                Don't have an account?
                <Link :href="route('register')" class="font-semibold text-primary-700 hover:text-primary-800">Create one</Link>
            </p>
        </form>
    </GuestLayout>
</template>
