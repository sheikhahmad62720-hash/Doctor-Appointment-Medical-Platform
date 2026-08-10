<script setup>
defineProps({
    eyebrow: { type: String, default: '' },
    title: { type: String, default: '' },
    description: { type: String, default: '' },
    align: { type: String, default: 'left', validator: (v) => ['left', 'center'].includes(v) },
});
</script>

<template>
    <div :class="['max-w-2xl', align === 'center' && 'mx-auto text-center']">
        <span v-if="eyebrow" class="section-eyebrow">
            <span class="h-1.5 w-1.5 rounded-full bg-primary-500" />
            {{ eyebrow }}
        </span>
        <h2 :class="['text-3xl font-bold sm:text-4xl', align === 'center' && 'mx-auto']">{{ title }}</h2>
        <p v-if="description" :class="['mt-3 text-base leading-relaxed text-slate-500', align === 'center' && 'mx-auto']">
            {{ description }}
        </p>
    </div>
</template>
