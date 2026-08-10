<script setup>
defineProps({
    lines: { type: Number, default: 3 },
    className: { type: String, default: '' },
});
</script>

<template>
    <div :class="className" :aria-busy="true" aria-label="Loading">
        <div v-for="i in lines" :key="i" class="skeleton h-3 animate-shimmer" :class="i === lines && lines > 1 ? 'w-2/3' : 'w-full'" />
    </div>
</template>
