<script setup>
defineProps({
    size: { type: String, default: 'md', validator: (v) => ['sm', 'md', 'lg'].includes(v) },
    tone: { type: String, default: 'light', validator: (v) => ['light', 'dark'].includes(v) },
});
</script>

<template>
    <span class="inline-flex items-center gap-2.5">
        <span
            :class="[
                'relative flex shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 shadow-sm ring-4',
                size === 'sm' ? 'h-8 w-8' : size === 'lg' ? 'h-11 w-11' : 'h-9 w-9',
                tone === 'dark' ? 'ring-primary-400/20' : 'ring-primary-600/15',
            ]"
        >
            <svg class="h-1/2 w-1/2 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 3.5h-4v6.5H3.5v4H10V20.5h4V14h6.5v-4H14V3.5z" fill="currentColor" />
            </svg>
        </span>
        <span :class="['flex flex-col leading-none', size === 'sm' ? 'gap-0.5' : 'gap-1']">
            <span
                :class="[
                    'font-extrabold tracking-tight',
                    size === 'sm' ? 'text-base' : size === 'lg' ? 'text-xl' : 'text-lg',
                    tone === 'dark' ? 'text-white' : 'text-navy-900',
                ]"
            >
                Medi<span class="text-primary-600">Care</span>
            </span>
            <span :class="['text-[10px] font-semibold uppercase tracking-[0.18em]', tone === 'dark' ? 'text-white/50' : 'text-slate-400']">
                Awais Malik Clinic
            </span>
        </span>
    </span>
</template>
