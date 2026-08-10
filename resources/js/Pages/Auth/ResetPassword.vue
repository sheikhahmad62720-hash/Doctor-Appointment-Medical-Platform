<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import Label from '@/Components/ui/Label.vue';
import FieldError from '@/Components/ui/FieldError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: { type: String, required: true },
    token: { type: String, required: true },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-navy-950">Choose a new password</h1>
            <p class="mt-1.5 text-sm text-slate-500">Enter a strong password you haven't used before.</p>
        </div>

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <Label for="email" value="Email address" required />
                <Input id="email" type="email" v-model="form.email" :error="!!form.errors.email" autocomplete="username" />
                <FieldError :message="form.errors.email" />
            </div>

            <div>
                <Label for="password" value="New password" required />
                <Input id="password" type="password" v-model="form.password" placeholder="At least 8 characters" :error="!!form.errors.password" autocomplete="new-password" />
                <FieldError :message="form.errors.password" />
            </div>

            <div>
                <Label for="password_confirmation" value="Confirm password" required />
                <Input id="password_confirmation" type="password" v-model="form.password_confirmation" placeholder="Repeat your password" :error="!!form.errors.password_confirmation" autocomplete="new-password" />
                <FieldError :message="form.errors.password_confirmation" />
            </div>

            <Button type="submit" class="w-full" size="lg" :loading="form.processing">Reset password</Button>
        </form>
    </GuestLayout>
</template>
