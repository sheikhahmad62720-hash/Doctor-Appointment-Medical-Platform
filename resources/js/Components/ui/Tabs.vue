<script setup>
import { computed, onMounted, provide, readonly, ref, toRef } from 'vue';

const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    tabs: { type: Array, required: true },
});

const emit = defineEmits(['update:modelValue']);

const internal = ref(props.modelValue);

const active = computed({
    get: () => props.modelValue ?? internal.value,
    set: (v) => {
        internal.value = v;
        emit('update:modelValue', v);
    },
});

if (!internal.value && props.tabs.length) internal.value = props.tabs[0].value;

provide('tabs', props.tabs);
provide('activeTab', readonly(toRef(active)));
</script>

<template>
    <div>
        <div class="flex gap-1 overflow-x-auto rounded-xl bg-slate-100 p-1 scrollbar-thin" role="tablist">
            <button
                v-for="tab in tabs"
                :key="tab.value"
                type="button"
                role="tab"
                :aria-selected="active === tab.value"
                :class="[
                    'flex shrink-0 items-center gap-2 rounded-lg px-3.5 py-2 text-sm font-semibold transition-all duration-200 focus-ring',
                    active === tab.value ? 'bg-white text-navy-900 shadow-soft' : 'text-slate-500 hover:text-navy-900',
                ]"
                @click="active = tab.value"
            >
                <component :is="tab.icon" v-if="tab.icon" class="h-4 w-4" />
                {{ tab.label }}
                <span
                    v-if="tab.count !== undefined"
                    :class="[
                        'rounded-full px-1.5 py-0.5 text-[10px] font-bold',
                        active === tab.value ? 'bg-primary-100 text-primary-700' : 'bg-slate-200 text-slate-500',
                    ]"
                >
                    {{ tab.count }}
                </span>
            </button>
        </div>
        <div class="mt-5">
            <slot />
        </div>
    </div>
</template>
