<script setup>
const model = defineModel({ type: [String, Number], default: '' });

defineProps({
    id: { type: String, default: null },
    placeholder: { type: String, default: 'Select an option' },
    error: { type: Boolean, default: false },
});
</script>

<template>
    <div class="relative">
        <select
            :id="id"
            v-model="model"
            :class="[
                'input-base appearance-none pr-10',
                error && 'input-error',
                !model && 'text-slate-400',
            ]"
        >
            <option value="" disabled>{{ placeholder }}</option>
            <slot />
        </select>
        <svg
            class="pointer-events-none absolute inset-y-0 right-3 my-auto h-4 w-4 text-slate-400"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
        >
            <path
                fill-rule="evenodd"
                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                clip-rule="evenodd"
            />
        </svg>
    </div>
</template>
