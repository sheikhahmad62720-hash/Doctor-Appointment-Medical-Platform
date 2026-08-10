<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import Label from '@/Components/ui/Label.vue';
import FieldError from '@/Components/ui/FieldError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-navy-950">Create your account</h1>
            <p class="mt-1.5 text-sm text-slate-500">Join MediCare to book appointments in under two minutes.</p>
        </div>

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <Label for="name" value="Full name" required />
                <Input id="name" v-model="form.name" placeholder="Jane Smith" :error="!!form.errors.name" autocomplete="name" autofocus />
                <FieldError :message="form.errors.name" />
            </div>

            <div>
                <Label for="email" value="Email address" required />
                <Input id="email" type="email" v-model="form.email" placeholder="you@example.com" :error="!!form.errors.email" autocomplete="username" />
                <FieldError :message="form.errors.email" />
            </div>

            <div>
                <Label for="phone" value="Phone number" />
                <Input id="phone" type="tel" v-model="form.phone" placeholder="+1 (555) 000-0000" :error="!!form.errors.phone" autocomplete="tel" />
                <FieldError :message="form.errors.phone" />
            </div>

            <div>
                <Label for="password" value="Password" required />
                <Input id="password" type="password" v-model="form.password" placeholder="At least 8 characters" :error="!!form.errors.password" autocomplete="new-password" />
                <FieldError :message="form.errors.password" />
            </div>

            <div>
                <Label for="password_confirmation" value="Confirm password" required />
                <Input id="password_confirmation" type="password" v-model="form.password_confirmation" placeholder="Repeat your password" :error="!!form.errors.password_confirmation" autocomplete="new-password" />
                <FieldError :message="form.errors.password_confirmation" />
            </div>

            <Button type="submit" class="w-full" size="lg" :loading="form.processing">Create account</Button>

            <p class="text-center text-sm text-slate-500">
                Already have an account?
                <Link :href="route('login')" class="font-semibold text-primary-700 hover:text-primary-800">Log in</Link>
            </p>
        </form>
    </GuestLayout>
</template>
