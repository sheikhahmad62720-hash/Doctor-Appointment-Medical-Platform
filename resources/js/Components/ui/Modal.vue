<script setup>
import { watch, onMounted, onBeforeUnmount } from 'vue';
import { XMarkIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    show: { type: Boolean, default: false },
    maxWidth: {
        type: String,
        default: '2xl',
        validator: (v) => ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl'].includes(v),
    },
    title: { type: String, default: '' },
    closeable: { type: Boolean, default: true },
});

const emit = defineEmits(['close']);

const sizes = {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
    '3xl': 'sm:max-w-3xl',
    '4xl': 'sm:max-w-4xl',
    '5xl': 'sm:max-w-5xl',
};

const close = () => {
    if (props.closeable) emit('close');
};

const onKeyDown = (e) => {
    if (e.key === 'Escape' && props.show) close();
};

watch(
    () => props.show,
    (show) => {
        document.body.style.overflow = show ? 'hidden' : '';
    },
);

onMounted(() => window.addEventListener('keydown', onKeyDown));
onBeforeUnmount(() => {
    window.removeEventListener('keydown', onKeyDown);
    document.body.style.overflow = '';
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 overflow-y-auto"
                aria-modal="true"
                role="dialog"
            >
                <div class="flex min-h-full items-end justify-center p-0 sm:items-center sm:p-4">
                    <div class="fixed inset-0 bg-navy-950/40 backdrop-blur-sm" @click="close" />

                    <Transition
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
                        enter-to-class="opacity-100 scale-100 translate-y-0"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div
                            v-if="show"
                            :class="[
                                'relative z-10 w-full rounded-t-3xl bg-white shadow-lifted sm:rounded-2xl',
                                sizes[maxWidth],
                            ]"
                        >
                            <div
                                v-if="title || $slots.header"
                                class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6"
                            >
                                <h3 class="text-base font-semibold text-navy-900">
                                    <slot name="header">{{ title }}</slot>
                                </h3>
                                <button
                                    v-if="closeable"
                                    type="button"
                                    class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-navy-900 focus-ring"
                                    @click="close"
                                    aria-label="Close"
                                >
                                    <XMarkIcon class="h-5 w-5" />
                                </button>
                            </div>

                            <div class="px-5 py-5 sm:px-6">
                                <slot />
                            </div>

                            <div v-if="$slots.footer" class="border-t border-slate-100 px-5 py-4 sm:px-6">
                                <slot name="footer" />
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
