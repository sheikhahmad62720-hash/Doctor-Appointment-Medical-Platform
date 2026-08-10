<script setup>
defineProps({
    columns: { type: Array, required: true },
    rows: { type: Array, default: () => [] },
    emptyTitle: { type: String, default: 'No records found' },
    emptyMessage: { type: String, default: 'There is nothing to display here yet.' },
    loading: { type: Boolean, default: false },
});
</script>

<template>
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-soft">
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full min-w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70">
                        <th
                            v-for="col in columns"
                            :key="col.key"
                            :class="[
                                'whitespace-nowrap px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-400 sm:px-5',
                                col.align === 'right' ? 'text-right' : col.align === 'center' ? 'text-center' : 'text-left',
                            ]"
                        >
                            {{ col.label }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template v-if="loading">
                        <tr v-for="i in 4" :key="i">
                            <td v-for="col in columns" :key="col.key" class="px-4 py-3.5 sm:px-5">
                                <div class="skeleton h-3.5 w-full animate-shimmer" :style="{ maxWidth: 40 + ((i * 13) % 40) + '%' }" />
                            </td>
                        </tr>
                    </template>
                    <template v-else-if="rows.length === 0">
                        <tr>
                            <td :colspan="columns.length" class="px-4 py-10 sm:px-5">
                                <div class="flex flex-col items-center text-center">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H6.911a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661z" />
                                        </svg>
                                    </div>
                                    <p class="mt-3 text-sm font-semibold text-navy-900">{{ emptyTitle }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ emptyMessage }}</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr
                            v-for="(row, index) in rows"
                            :key="row.id ?? index"
                            class="transition-colors duration-150 hover:bg-primary-50/40"
                        >
                            <td
                                v-for="col in columns"
                                :key="col.key"
                                :class="[
                                    'px-4 py-3.5 align-middle text-navy-900 sm:px-5',
                                    col.align === 'right' ? 'text-right' : col.align === 'center' ? 'text-center' : 'text-left',
                                    col.class,
                                ]"
                            >
                                <slot :name="'cell-' + col.key" :row="row" :value="row[col.key]">
                                    {{ row[col.key] }}
                                </slot>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>
