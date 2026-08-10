<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import Label from '@/Components/ui/Label.vue';
import FieldError from '@/Components/ui/FieldError.vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold text-navy-950">Update password</h2>
            <p class="mt-1 text-sm text-slate-500">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form class="mt-6 space-y-5" @submit.prevent="updatePassword">
            <div>
                <Label for="current_password" value="Current password" required />
                <Input id="current_password" ref="currentPasswordInput" v-model="form.current_password" type="password" :error="!!form.errors.current_password" autocomplete="current-password" />
                <FieldError :message="form.errors.current_password" />
            </div>

            <div>
                <Label for="password" value="New password" required />
                <Input id="password" ref="passwordInput" v-model="form.password" type="password" :error="!!form.errors.password" autocomplete="new-password" />
                <FieldError :message="form.errors.password" />
            </div>

            <div>
                <Label for="password_confirmation" value="Confirm password" required />
                <Input id="password_confirmation" v-model="form.password_confirmation" type="password" :error="!!form.errors.password_confirmation" autocomplete="new-password" />
                <FieldError :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center gap-4">
                <Button type="submit" :loading="form.processing">Save password</Button>
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
