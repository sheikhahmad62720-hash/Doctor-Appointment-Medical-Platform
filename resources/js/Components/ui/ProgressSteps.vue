<script setup>
defineProps({
    steps: { type: Array, default: () => [] },
    current: { type: Number, default: 0 },
});
</script>

<template>
    <nav aria-label="Progress">
        <ol class="flex items-center gap-2 sm:gap-3">
            <li v-for="(step, index) in steps" :key="index" class="flex flex-1 items-center gap-2 sm:gap-3">
                <div class="flex flex-col items-center gap-1.5 sm:flex-row sm:gap-2">
                    <span
                        :class="[
                            'flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 text-xs font-bold transition-all duration-300',
                            index < current
                                ? 'border-primary-600 bg-primary-600 text-white'
                                : index === current
                                  ? 'border-primary-600 bg-white text-primary-700 ring-4 ring-primary-500/10'
                                  : 'border-slate-200 bg-white text-slate-400',
                        ]"
                    >
                        <svg
                            v-if="index < current"
                            class="h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <template v-else>{{ String(index + 1).padStart(2, '0') }}</template>
                    </span>
                    <span
                        :class="[
                            'text-xs font-semibold transition-colors duration-200 sm:text-[13px]',
                            index <= current ? 'text-navy-900' : 'text-slate-400',
                        ]"
                    >
                        {{ step }}
                    </span>
                </div>
                <div
                    v-if="index < steps.length - 1"
                    :class="[
                        'hidden h-px flex-1 bg-slate-200 transition-colors duration-300 sm:block',
                        index < current && 'bg-primary-500',
                    ]"
                />
            </li>
        </ol>
    </nav>
</template>
