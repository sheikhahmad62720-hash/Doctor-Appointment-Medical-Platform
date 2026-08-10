<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import Label from '@/Components/ui/Label.vue';
import FieldError from '@/Components/ui/FieldError.vue';
import Modal from '@/Components/ui/Modal.vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-bold text-navy-950">Delete account</h2>
            <p class="mt-1 text-sm text-slate-500">
                Once your account is deleted, all of its resources and data will be permanently deleted.
            </p>
        </header>

        <div class="rounded-2xl border border-red-100 bg-red-50/50 p-5">
            <p class="text-sm leading-relaxed text-red-700">
                This action is permanent and cannot be undone. If you're sure, you can delete your account below.
            </p>
            <Button variant="danger" class="mt-4" @click="confirmUserDeletion">Delete account</Button>
        </div>

        <Modal :show="confirmingUserDeletion" @close="closeModal" :closeable="!form.processing" title="Delete account">
            <div class="text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-500 ring-8 ring-red-50/60">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-navy-900">Are you sure?</h3>
                <p class="mx-auto mt-1.5 max-w-sm text-sm leading-relaxed text-slate-500">
                    Once your account is deleted, all of its resources and data will be permanently deleted.
                    Please enter your password to confirm.
                </p>
            </div>

            <div class="mt-6">
                <Label for="delete-password" value="Password" class="sr-only" />
                <Input id="delete-password" ref="passwordInput" v-model="form.password" type="password" placeholder="Your password" :error="!!form.errors.password" @keyup.enter="deleteUser" />
                <FieldError :message="form.errors.password" />
            </div>

            <template #footer>
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <Button variant="ghost" :disabled="form.processing" @click="closeModal">Cancel</Button>
                    <Button variant="danger" :loading="form.processing" @click="deleteUser">Delete account</Button>
                </div>
            </template>
        </Modal>
    </section>
</template>
