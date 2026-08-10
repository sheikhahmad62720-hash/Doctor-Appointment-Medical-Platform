<script setup>
import { toasts, dismiss } from '@/lib/toast';
import {
    CheckCircleIcon,
    XCircleIcon,
    InformationCircleIcon,
    ExclamationTriangleIcon,
    XMarkIcon,
} from '@heroicons/vue/20/solid';

const icons = {
    success: CheckCircleIcon,
    error: XCircleIcon,
    info: InformationCircleIcon,
    warning: ExclamationTriangleIcon,
};

const iconClasses = {
    success: 'text-emerald-400',
    error: 'text-red-400',
    info: 'text-sky-400',
    warning: 'text-amber-400',
};
</script>

<template>
    <Teleport to="body">
        <div class="pointer-events-none fixed inset-x-0 bottom-0 z-[60] flex flex-col items-center gap-2 p-4 sm:items-end sm:p-6" aria-live="polite">
            <TransitionGroup
                enter-active-class="transition duration-300 ease-out-soft"
                enter-from-class="opacity-0 translate-y-3 scale-95"
                enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-for="t in toasts"
                    :key="t.id"
                    class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-2xl border border-slate-200/80 bg-white/95 p-4 shadow-lifted backdrop-blur"
                >
                    <component :is="icons[t.tone] || icons.info" :class="['h-5 w-5 shrink-0', iconClasses[t.tone] || iconClasses.info]" />
                    <div class="min-w-0 flex-1">
                        <p v-if="t.title" class="text-sm font-semibold text-navy-900">{{ t.title }}</p>
                        <p class="text-sm leading-relaxed text-slate-600">{{ t.message }}</p>
                    </div>
                    <button type="button" class="rounded-md p-1 text-slate-400 transition hover:bg-slate-100 hover:text-navy-900" @click="dismiss(t.id)" aria-label="Dismiss notification">
                        <XMarkIcon class="h-4 w-4" />
                    </button>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>
