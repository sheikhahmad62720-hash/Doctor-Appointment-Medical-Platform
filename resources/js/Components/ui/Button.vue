<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: (v) =>
            ['primary', 'secondary', 'outline', 'ghost', 'danger', 'success'].includes(v),
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(v),
    },
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    fullWidth: { type: Boolean, default: false },
    type: { type: String, default: 'button' },
});

const variants = {
    primary:
        'bg-primary-600 text-white shadow-sm hover:bg-primary-700 focus-visible:ring-primary-500/30 active:bg-primary-800 disabled:hover:bg-primary-600',
    secondary:
        'bg-primary-50 text-primary-700 hover:bg-primary-100 focus-visible:ring-primary-500/30 active:bg-primary-200',
    outline:
        'border border-slate-300 bg-white text-navy-900 shadow-sm hover:border-primary-300 hover:bg-primary-50 hover:text-primary-700 focus-visible:ring-primary-500/20 active:bg-primary-100',
    ghost:
        'text-slate-600 hover:bg-slate-100 hover:text-navy-900 focus-visible:ring-slate-500/20 active:bg-slate-200',
    danger:
        'bg-red-600 text-white shadow-sm hover:bg-red-700 focus-visible:ring-red-500/30 active:bg-red-800 disabled:hover:bg-red-600',
    success:
        'bg-emerald-600 text-white shadow-sm hover:bg-emerald-700 focus-visible:ring-emerald-500/30 active:bg-emerald-800',
};

const sizes = {
    xs: 'px-2.5 py-1.5 text-xs gap-1.5',
    sm: 'px-3 py-2 text-sm gap-2',
    md: 'px-4 py-2.5 text-sm gap-2',
    lg: 'px-5 py-3 text-[15px] gap-2.5',
    xl: 'px-6 py-3.5 text-base gap-2.5',
};

const classes = computed(() => [
    variants[props.variant],
    sizes[props.size],
    'inline-flex items-center justify-center rounded-xl font-semibold transition-all duration-150 focus:outline-none focus-visible:ring-4 disabled:pointer-events-none disabled:opacity-50',
    props.fullWidth ? 'w-full' : '',
]);
</script>

<template>
    <button
        :type="type"
        :disabled="disabled || loading"
        :class="classes"
    >
        <svg
            v-if="loading"
            class="h-4 w-4 animate-spin"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
            />
            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            />
        </svg>
        <slot />
    </button>
</template>
