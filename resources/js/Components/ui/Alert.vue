<script setup>
import { computed } from 'vue';
import { InformationCircleIcon, CheckCircleIcon, XCircleIcon, ExclamationTriangleIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    tone: {
        type: String,
        default: 'info',
        validator: (v) => ['info', 'success', 'error', 'warning'].includes(v),
    },
    title: { type: String, default: '' },
    closable: { type: Boolean, default: false },
});

const emit = defineEmits(['close']);
const visible = defineModel({ type: Boolean, default: true });

const tones = {
    info: { wrap: 'bg-sky-50 border-sky-100 text-sky-800', icon: InformationCircleIcon, iconColor: 'text-sky-500' },
    success: { wrap: 'bg-emerald-50 border-emerald-100 text-emerald-800', icon: CheckCircleIcon, iconColor: 'text-emerald-500' },
    error: { wrap: 'bg-red-50 border-red-100 text-red-800', icon: XCircleIcon, iconColor: 'text-red-500' },
    warning: { wrap: 'bg-amber-50 border-amber-100 text-amber-800', icon: ExclamationTriangleIcon, iconColor: 'text-amber-500' },
};

const current = computed(() => tones[props.tone]);
</script>

<template>
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 scale-[0.98]"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="visible" :class="['flex items-start gap-3 rounded-xl border p-4', current.wrap]">
            <component :is="current.icon" :class="['mt-0.5 h-5 w-5 shrink-0', current.iconColor]" />
            <div class="flex-1 text-sm leading-relaxed">
                <p v-if="title" class="mb-0.5 font-semibold">{{ title }}</p>
                <div class="text-current opacity-90"><slot /></div>
            </div>
            <button
                v-if="closable"
                type="button"
                @click="visible = false"
                class="rounded-md p-1 transition hover:bg-black/5"
                :aria-label="'Dismiss ' + title"
            >
                <svg class="h-4 w-4 opacity-60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                </svg>
            </button>
        </div>
    </Transition>
</template>
