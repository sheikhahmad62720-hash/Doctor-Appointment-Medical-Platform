<script setup>
import { useForm, usePage, Link } from '@inertiajs/vue3';
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import Label from '@/Components/ui/Label.vue';
import FieldError from '@/Components/ui/FieldError.vue';
import Alert from '@/Components/ui/Alert.vue';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    phone: user.phone ?? '',
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold text-navy-950">Profile information</h2>
            <p class="mt-1 text-sm text-slate-500">Update your account's profile information and email address.</p>
        </header>

        <form class="mt-6 space-y-5" @submit.prevent="form.patch(route('profile.update'))">
            <div>
                <Label for="name" value="Full name" required />
                <Input id="name" v-model="form.name" :error="!!form.errors.name" required autocomplete="name" />
                <FieldError :message="form.errors.name" />
            </div>

            <div>
                <Label for="email" value="Email address" required />
                <Input id="email" type="email" v-model="form.email" :error="!!form.errors.email" required autocomplete="username" />
                <FieldError :message="form.errors.email" />
            </div>

            <div>
                <Label for="phone" value="Phone number" />
                <Input id="phone" type="tel" v-model="form.phone" :error="!!form.errors.phone" autocomplete="tel" />
                <FieldError :message="form.errors.phone" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <Alert tone="warning" title="Email not verified">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button" class="font-semibold underline">
                        Click here to re-send the verification email.
                    </Link>
                </Alert>
                <Alert v-if="status === 'verification-link-sent'" tone="success" title="Verification link sent">
                    A new verification link has been sent to your email address.
                </Alert>
            </div>

            <div class="flex items-center gap-4">
                <Button type="submit" :loading="form.processing">Save changes</Button>
                <Transition
                    enter-active-class="transition duration-150 ease-out"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition duration-100 ease-in"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm font-medium text-emerald-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
