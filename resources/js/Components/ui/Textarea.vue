<script setup>
const model = defineModel({ type: String, default: '' });

defineProps({
    id: { type: String, default: null },
    placeholder: { type: String, default: '' },
    error: { type: Boolean, default: false },
    rows: { type: Number, default: 4 },
});
</script>

<template>
    <textarea
        :id="id"
        v-model="model"
        :rows="rows"
        :placeholder="placeholder"
        :class="['input-base resize-y', error && 'input-error']"
    />
</template>
