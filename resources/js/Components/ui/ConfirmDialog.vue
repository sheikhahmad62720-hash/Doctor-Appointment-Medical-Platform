<script setup>
import Modal from './Modal.vue';
import Button from './Button.vue';
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline';

defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: 'Are you sure?' },
    message: { type: String, default: '' },
    confirmText: { type: String, default: 'Confirm' },
    loading: { type: Boolean, default: false },
});

const emit = defineEmits(['confirm', 'close']);
</script>

<template>
    <Modal :show="show" max-width="sm" :closeable="!loading" @close="emit('close')">
        <div class="flex flex-col items-center text-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 ring-8 ring-red-50/60">
                <ExclamationTriangleIcon class="h-6 w-6 text-red-500" />
            </div>
            <h3 class="mt-4 text-lg font-semibold text-navy-900">{{ title }}</h3>
            <p v-if="message" class="mt-1.5 text-sm leading-relaxed text-slate-500">{{ message }}</p>
        </div>
        <template #footer>
            <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <Button variant="ghost" :disabled="loading" @click="emit('close')">Cancel</Button>
                <Button variant="danger" :loading="loading" @click="emit('confirm')">{{ confirmText }}</Button>
            </div>
        </template>
    </Modal>
</template>
