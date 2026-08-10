<script setup>
import { computed } from 'vue';

const props = defineProps({
    tone: {
        type: String,
        default: 'slate',
        validator: (v) =>
            ['slate', 'primary', 'green', 'amber', 'red', 'navy', 'sky'].includes(v),
    },
    dot: { type: Boolean, default: false },
    size: { type: String, default: 'sm', validator: (v) => ['xs', 'sm', 'md'].includes(v) },
});

const tones = {
    slate: 'bg-slate-100 text-slate-600 ring-slate-200',
    primary: 'bg-primary-50 text-primary-700 ring-primary-100',
    green: 'bg-emerald-50 text-emerald-700 ring-emerald-100',
    amber: 'bg-amber-50 text-amber-700 ring-amber-100',
    red: 'bg-red-50 text-red-700 ring-red-100',
    navy: 'bg-navy-50 text-navy-700 ring-navy-100',
    sky: 'bg-sky-50 text-sky-700 ring-sky-100',
};

const dots = {
    slate: 'bg-slate-400',
    primary: 'bg-primary-500',
    green: 'bg-emerald-500',
    amber: 'bg-amber-500',
    red: 'bg-red-500',
    navy: 'bg-navy-500',
    sky: 'bg-sky-500',
};

const sizes = {
    xs: 'px-2 py-0.5 text-[11px] gap-1',
    sm: 'px-2.5 py-0.5 text-xs gap-1.5',
    md: 'px-3 py-1 text-sm gap-1.5',
};

const classes = computed(() => [tones[props.tone], sizes[props.size]]);
</script>

<template>
    <span :class="['inline-flex items-center rounded-full font-semibold ring-1 ring-inset', classes]">
        <span v-if="dot" :class="['h-1.5 w-1.5 rounded-full', dots[tone]]" />
        <slot />
    </span>
</template>
