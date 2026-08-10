<script setup>
defineProps({
    value: { type: String, default: '' },
    for: { type: String, default: null },
    required: { type: Boolean, default: false },
});
</script>

<template>
    <label :for="for" class="label-base">
        <span>{{ value }}</span>
        <span v-if="required" class="text-red-500">*</span>
        <slot />
    </label>
</template>
