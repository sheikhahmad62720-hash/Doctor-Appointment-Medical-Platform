<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({ type: [String, Number], default: '' });

defineProps({
    id: { type: String, default: null },
    type: { type: String, default: 'text' },
    placeholder: { type: String, default: '' },
    error: { type: Boolean, default: false },
    prefix: { type: String, default: null },
    suffix: { type: String, default: null },
});

const input = ref(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) input.value.focus();
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <div class="relative">
        <span
            v-if="prefix"
            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-medium text-slate-400"
        >
            {{ prefix }}
        </span>
        <input
            :id="id"
            ref="input"
            :type="type"
            v-model="model"
            :placeholder="placeholder"
            :class="[
                'input-base',
                error && 'input-error',
                prefix && 'pl-10',
                suffix && 'pr-16',
            ]"
        />
        <span
            v-if="suffix"
            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-sm font-medium text-slate-400"
        >
            {{ suffix }}
        </span>
    </div>
</template>
