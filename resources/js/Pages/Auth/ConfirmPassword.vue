<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import Label from '@/Components/ui/Label.vue';
import FieldError from '@/Components/ui/FieldError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-navy-950">Confirm your password</h1>
            <p class="mt-1.5 text-sm text-slate-500">
                This is a secure area of the application. Please confirm your password before continuing.
            </p>
        </div>

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <Label for="password" value="Password" required />
                <Input id="password" type="password" v-model="form.password" :error="!!form.errors.password" autocomplete="current-password" autofocus />
                <FieldError :message="form.errors.password" />
            </div>
            <Button type="submit" class="w-full" size="lg" :loading="form.processing">Confirm</Button>
        </form>
    </GuestLayout>
</template>
