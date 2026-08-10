<script setup>
import { computed } from 'vue';

const props = defineProps({
    icon: { type: Object, default: null },
    label: { type: String, default: '' },
    value: { type: [String, Number], default: '' },
    hint: { type: String, default: '' },
    tone: {
        type: String,
        default: 'primary',
        validator: (v) => ['primary', 'navy', 'emerald', 'amber', 'sky'].includes(v),
    },
});

const tones = {
    primary: { wrap: 'bg-primary-50 text-primary-600 ring-primary-100' },
    navy: { wrap: 'bg-navy-50 text-navy-600 ring-navy-100' },
    emerald: { wrap: 'bg-emerald-50 text-emerald-600 ring-emerald-100' },
    amber: { wrap: 'bg-amber-50 text-amber-600 ring-amber-100' },
    sky: { wrap: 'bg-sky-50 text-sky-600 ring-sky-100' },
};

const toneClass = computed(() => tones[props.tone].wrap);
</script>

<template>
    <div class="card flex items-start justify-between gap-3 p-5">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ label }}</p>
            <p class="mt-1 text-3xl font-extrabold text-navy-950">{{ value }}</p>
            <p v-if="hint" class="mt-0.5 text-xs text-slate-400">{{ hint }}</p>
        </div>
        <div v-if="icon" :class="['flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ring-1', toneClass]">
            <component :is="icon" class="h-5 w-5" />
        </div>
        <slot />
    </div>
</template>
