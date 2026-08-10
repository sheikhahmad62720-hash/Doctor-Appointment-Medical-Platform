<script setup>
defineProps({
    padded: { type: Boolean, default: true },
    hover: { type: Boolean, default: false },
});
</script>

<template>
    <div :class="['card', padded && 'p-5 sm:p-6', hover && 'card-hover']">
        <slot />
    </div>
</template>
