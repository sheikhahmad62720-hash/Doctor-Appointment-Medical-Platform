<script setup>
defineProps({
    delivered: { type: Boolean, default: false },
    read: { type: Boolean, default: false },
});
</script>

<template>
    <svg
        viewBox="0 0 18 12"
        class="h-3.5 w-3.5"
        :class="read ? 'text-green-500' : 'text-slate-400'"
        fill="none"
        stroke="currentColor"
        stroke-width="1.7"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M1.5 6.5 5 10 11.5 3" />
        <path v-if="delivered" d="M6.5 6.5 10 10 16.5 3" />
    </svg>
</template>
