<script setup>
import { ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Brand from '@/Components/ui/Brand.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import { Bars3Icon, XMarkIcon, ChevronRightIcon, ArrowRightStartOnRectangleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    navItems: { type: Array, default: () => [] },
    title: { type: String, default: '' },
});

const page = usePage();
const user = page.props.auth?.user;

const sidebarOpen = ref(false);

watch(
    () => page.url,
    () => {
        sidebarOpen.value = false;
        window.scrollTo({ top: 0, behavior: 'instant' });
    },
);

const isActive = (item) => {
    const current = route().current();
    if (item.match) return item.match.some((r) => current === r);
    return current === item.route;
};

const roleLabel = () => {
    if (user?.role === 'doctor') return 'Doctor';
    if (user?.role === 'admin') return 'Administrator';
    return 'Patient';
};
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <ToastContainer />

        <!-- Mobile top bar -->
        <div class="sticky top-0 z-40 flex h-16 items-center justify-between border-b border-slate-200 bg-white/80 px-4 backdrop-blur-xl lg:hidden">
            <Link :href="route('home')" class="shrink-0">
                <Brand size="sm" />
            </Link>
            <button
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-navy-900 shadow-soft"
                @click="sidebarOpen = true"
                aria-label="Open menu"
            >
                <Bars3Icon class="h-5 w-5" />
            </button>
        </div>

        <!-- Desktop sidebar -->
        <aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col bg-navy-950 lg:flex">
            <div class="flex h-16 items-center border-b border-white/10 px-5">
                <Link :href="route('home')"><Brand tone="dark" /></Link>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5 scrollbar-thin" aria-label="Dashboard">
                <Link
                    v-for="item in navItems"
                    :key="item.route"
                    :href="item.external ? item.route : route(item.route)"
                    :class="[
                        'group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-150',
                        isActive(item)
                            ? 'bg-primary-600/15 text-primary-300 ring-1 ring-primary-500/20'
                            : 'text-white/60 hover:bg-white/5 hover:text-white',
                    ]"
                >
                    <component :is="item.icon" class="h-5 w-5 shrink-0" :class="isActive(item) ? 'text-primary-400' : 'text-white/40 group-hover:text-white/70'" />
                    {{ item.label }}
                </Link>

                <p class="px-3.5 pb-2 pt-7 text-[11px] font-bold uppercase tracking-wider text-white/30">Practice</p>
                <Link
                    :href="route('services')"
                    class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-white/60 transition hover:bg-white/5 hover:text-white"
                >
                    <svg class="h-5 w-5 text-white/40 group-hover:text-white/70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    Services
                </Link>
                <Link
                    :href="route('home')"
                    class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-white/60 transition hover:bg-white/5 hover:text-white"
                >
                    <svg class="h-5 w-5 text-white/40 group-hover:text-white/70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
                    </svg>
                    Visit Website
                </Link>
            </nav>

            <div class="border-t border-white/10 p-4">
                <div class="flex items-center gap-3">
                    <Avatar :name="user?.name" size="sm" tone="sky" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-white">{{ user?.name }}</p>
                        <p class="text-xs text-white/40">{{ roleLabel() }}</p>
                    </div>
                    <Link :href="route('logout')" method="post" as="button" class="rounded-lg p-2 text-white/40 transition hover:bg-white/5 hover:text-white" aria-label="Log out">
                        <ArrowRightStartOnRectangleIcon class="h-5 w-5" />
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Mobile sidebar drawer -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="sidebarOpen" class="fixed inset-0 z-50 lg:hidden">
                <div class="fixed inset-0 bg-navy-950/50 backdrop-blur-sm" @click="sidebarOpen = false" />
                <Transition
                    enter-active-class="transition duration-300 ease-out-soft"
                    enter-from-class="-translate-x-full"
                    enter-to-class="translate-x-0"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="translate-x-0"
                    leave-to-class="-translate-x-full"
                >
                    <div class="absolute inset-y-0 left-0 flex w-72 flex-col bg-navy-950 shadow-lifted">
                        <div class="flex h-16 items-center justify-between border-b border-white/10 px-5">
                            <Link :href="route('home')"><Brand tone="dark" /></Link>
                            <button type="button" class="rounded-lg p-1.5 text-white/50 transition hover:bg-white/5 hover:text-white" @click="sidebarOpen = false" aria-label="Close menu">
                                <XMarkIcon class="h-5 w-5" />
                            </button>
                        </div>
                        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5 scrollbar-thin" aria-label="Dashboard">
                            <Link
                                v-for="item in navItems"
                                :key="item.route"
                                :href="item.external ? item.route : route(item.route)"
                                :class="[
                                    'flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium',
                                    isActive(item) ? 'bg-primary-600/15 text-primary-300' : 'text-white/60 hover:bg-white/5 hover:text-white',
                                ]"
                                @click="sidebarOpen = false"
                            >
                                <component :is="item.icon" class="h-5 w-5" />
                                {{ item.label }}
                            </Link>
                        </nav>
                        <div class="border-t border-white/10 p-4">
                            <div class="flex items-center gap-3">
                                <Avatar :name="user?.name" size="sm" tone="sky" />
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-white">{{ user?.name }}</p>
                                    <p class="text-xs text-white/40">{{ roleLabel() }}</p>
                                </div>
                                <Link :href="route('logout')" method="post" as="button" class="rounded-lg p-2 text-white/40 transition hover:bg-white/5 hover:text-white">
                                    <ArrowRightStartOnRectangleIcon class="h-5 w-5" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>

        <!-- Main content -->
        <div class="flex min-h-screen flex-col lg:pl-64">
            <main class="flex-1 px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
                <slot />
            </main>
        </div>
    </div>
</template>
