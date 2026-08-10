<script setup>
import { watch, onMounted, onBeforeUnmount } from 'vue';
import { XMarkIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: '' },
    closeable: { type: Boolean, default: true },
});

const emit = defineEmits(['close']);

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
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-50" aria-modal="true" role="dialog">
                <div class="fixed inset-0 bg-navy-950/40 backdrop-blur-sm" @click="close" />
                <Transition
                    enter-active-class="transition duration-300 ease-out-soft"
                    enter-from-class="translate-x-full"
                    enter-to-class="translate-x-0"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="translate-x-0"
                    leave-to-class="translate-x-full"
                >
                    <div
                        v-if="show"
                        class="absolute inset-y-0 right-0 flex w-full max-w-md flex-col bg-white shadow-lifted"
                    >
                        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
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
                        <div class="flex-1 overflow-y-auto px-5 py-5 scrollbar-thin">
                            <slot />
                        </div>
                        <div v-if="$slots.footer" class="border-t border-slate-100 px-5 py-4">
                            <slot name="footer" />
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
