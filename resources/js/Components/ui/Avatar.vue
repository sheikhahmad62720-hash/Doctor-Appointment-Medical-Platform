<script setup>
import { computed } from 'vue';

const props = defineProps({
    name: { type: String, default: '' },
    src: { type: String, default: null },
    size: { type: String, default: 'md', validator: (v) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(v) },
    tone: { type: String, default: 'primary' },
});

const sizes = {
    xs: 'h-7 w-7 text-[11px]',
    sm: 'h-9 w-9 text-xs',
    md: 'h-10 w-10 text-sm',
    lg: 'h-12 w-12 text-base',
    xl: 'h-16 w-16 text-lg',
};

const tones = {
    primary: 'bg-primary-100 text-primary-700',
    navy: 'bg-navy-100 text-navy-700',
    amber: 'bg-amber-100 text-amber-700',
    sky: 'bg-sky-100 text-sky-700',
    violet: 'bg-violet-100 text-violet-700',
    emerald: 'bg-emerald-100 text-emerald-700',
    rose: 'bg-rose-100 text-rose-700',
};

const initials = computed(() =>
    props.name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((w) => w[0].toUpperCase())
        .join(''),
);
</script>

<template>
    <img v-if="src" :src="src" :alt="name" :class="['rounded-full object-cover ring-2 ring-white', sizes[size]]" />
    <span v-else :class="['flex shrink-0 select-none items-center justify-center rounded-full font-bold ring-2 ring-white', tones[tone], sizes[size]]">
        {{ initials || '?' }}
    </span>
</template>
