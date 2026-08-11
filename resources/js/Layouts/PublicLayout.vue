<script setup>
import { ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Brand from '@/Components/ui/Brand.vue';
import site from '@/config/site.js';
import {
    ChevronDownIcon,
    PhoneIcon,
    ClockIcon,
    MapPinIcon,
    ShieldCheckIcon,
    StarIcon,
    Bars3Icon,
    XMarkIcon,
    ArrowRightIcon,
} from '@heroicons/vue/24/outline';

const page = usePage();
const user = () => page.props.auth?.user ?? null;
const doctor = site.doctor;

const showingMobile = ref(false);

watch(
    () => page.url,
    () => {
        showingMobile.value = false;
        window.scrollTo({ top: 0, behavior: 'instant' });
    },
);

const links = [
    { label: 'Home', route: 'home' },
    { label: 'About', route: 'about' },
    { label: 'Services', route: 'services' },
    { label: 'Appointments', route: 'dashboard' },
    { label: 'Contact', route: 'contact' },
];

const isActive = (name) => route().current(name);

const navHref = (name) => {
    if (name === 'dashboard' && !user()) return route('login');
    return route(name);
};
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <ToastContainer />

        <!-- Announcement bar -->
        <div class="hidden bg-navy-950 text-white md:block">
            <div class="container-px flex h-9 items-center justify-between text-xs">
                <div class="flex items-center gap-6">
                    <span class="inline-flex items-center gap-1.5 text-white/70">
                        <PhoneIcon class="h-3.5 w-3.5" />
                        +92 300 1234567
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-white/70">
                        <ClockIcon class="h-3.5 w-3.5" />
                        FMH Shadman (Morning) · Mid City Jail Road (Evening)
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-white/70">
                        <MapPinIcon class="h-3.5 w-3.5" />
                        Shadman &amp; Jail Road, Lahore
                    </span>
                </div>
                <span class="inline-flex items-center gap-1.5 font-medium text-primary-300">
                    <ShieldCheckIcon class="h-3.5 w-3.5" />
                    Professor of Surgery · 10+ years experience
                </span>
            </div>
        </div>

        <!-- Navbar -->
        <header class="sticky top-0 z-40 border-b border-slate-200/60 bg-white/80 backdrop-blur-xl">
            <div class="container-px">
                <nav class="flex h-16 items-center justify-between gap-4 sm:h-[72px]" aria-label="Main">
                    <Link :href="route('home')" class="shrink-0 rounded-xl focus-ring">
                        <Brand />
                    </Link>

                    <div class="hidden items-center gap-1 lg:flex">
                        <Link
                            v-for="link in links"
                            :key="link.route"
                            :href="navHref(link.route)"
                            :class="[
                                'relative rounded-lg px-3.5 py-2 text-sm font-medium transition-colors duration-150',
                                isActive(link.route)
                                    ? 'text-primary-700'
                                    : 'text-slate-600 hover:bg-slate-100/80 hover:text-navy-900',
                            ]"
                        >
                            {{ link.label }}
                            <span
                                v-if="isActive(link.route)"
                                class="absolute inset-x-3.5 -bottom-[21px] h-0.5 rounded-full bg-primary-600"
                            />
                        </Link>
                    </div>

                    <div class="hidden items-center gap-3 lg:flex">
                        <template v-if="user()">
                            <Link
                                v-if="user().role === 'admin'"
                                :href="route('admin.dashboard')"
                                class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 transition hover:text-navy-900"
                            >
                                Admin Panel
                            </Link>
                            <Link
                                v-else
                                :href="route('dashboard')"
                                class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 transition hover:text-navy-900"
                            >
                                My Appointments
                            </Link>
                            <Link
                                :href="route('booking.create')"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-150 hover:bg-primary-700 focus-ring active:bg-primary-800"
                            >
                                Book Appointment
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 transition hover:text-navy-900"
                            >
                                Login
                            </Link>
                            <Link
                                :href="route('booking.create')"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-150 hover:bg-primary-700 focus-ring active:bg-primary-800"
                            >
                                Book Appointment
                            </Link>
                        </template>
                    </div>

                    <!-- Mobile toggle -->
                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-navy-900 shadow-soft lg:hidden"
                        @click="showingMobile = !showingMobile"
                        :aria-expanded="showingMobile"
                        aria-label="Toggle menu"
                    >
                        <Bars3Icon v-if="!showingMobile" class="h-5 w-5" />
                        <XMarkIcon v-else class="h-5 w-5" />
                    </button>
                </nav>
            </div>

            <!-- Mobile menu -->
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div v-if="showingMobile" class="border-t border-slate-200/70 bg-white lg:hidden">
                    <div class="container-px space-y-1 py-4">
                        <Link
                            v-for="link in links"
                            :key="link.route"
                            :href="navHref(link.route)"
                            :class="[
                                'flex items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold transition',
                                isActive(link.route)
                                    ? 'bg-primary-50 text-primary-700'
                                    : 'text-navy-900 hover:bg-slate-50',
                            ]"
                        >
                            {{ link.label }}
                            <ChevronDownIcon v-if="link.route === 'dashboard'" class="h-4 w-4 -rotate-90 text-slate-400" />
                        </Link>

                        <div class="grid grid-cols-1 gap-2 pt-3">
                            <template v-if="user()">
                                <Link
                                    :href="route('dashboard')"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-navy-900 transition hover:border-primary-300 hover:bg-primary-50"
                                >
                                    My Appointments
                                </Link>
                                <Link
                                    :href="route('booking.create')"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700"
                                >
                                    Book Appointment
                                    <ArrowRightIcon class="h-4 w-4" />
                                </Link>
                            </template>
                            <template v-else>
                                <Link
                                    :href="route('register')"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-navy-900 transition hover:border-primary-300 hover:bg-primary-50"
                                >
                                    Create an account
                                </Link>
                                <Link
                                    :href="route('booking.create')"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700"
                                >
                                    Book Appointment
                                    <ArrowRightIcon class="h-4 w-4" />
                                </Link>
                            </template>
                        </div>
                    </div>
                </div>
            </Transition>
        </header>

        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="mt-24 border-t border-slate-200 bg-white">
            <div class="container-px py-14">
                <div class="grid grid-cols-2 gap-10 md:grid-cols-4">
                    <div class="col-span-2 md:col-span-1">
                        <Brand />
                        <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-500">
                            Advanced bariatric, laparoscopic and general surgery — delivered with care in Lahore for over 10 years.
                        </p>
                        <div class="mt-5 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-100">
                                <StarIcon class="h-3.5 w-3.5" />
                                {{ doctor.rating }} patient rating
                            </span>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-navy-900">Explore</h4>
                        <ul class="mt-4 space-y-2.5">
                            <li v-for="link in links" :key="link.route">
                                <Link :href="navHref(link.route)" class="text-sm text-slate-500 transition hover:text-primary-700">
                                    {{ link.label }}
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-navy-900">Services</h4>
                        <ul class="mt-4 space-y-2.5">
                            <li><Link :href="route('services')" class="text-sm text-slate-500 transition hover:text-primary-700">Bariatric Surgery</Link></li>
                            <li><Link :href="route('services')" class="text-sm text-slate-500 transition hover:text-primary-700">Laparoscopic Surgery</Link></li>
                            <li><Link :href="route('services')" class="text-sm text-slate-500 transition hover:text-primary-700">General Surgery</Link></li>
                            <li><Link :href="route('services')" class="text-sm text-slate-500 transition hover:text-primary-700">Thyroid &amp; Breast Surgery</Link></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-navy-900">Contact</h4>
                        <ul class="mt-4 space-y-2.5 text-sm text-slate-500">
                            <li class="flex items-start gap-2.5">
                                <MapPinIcon class="mt-0.5 h-4 w-4 shrink-0 text-primary-600" />
                                Fatima Memorial Hospital, Shadman, Lahore
                            </li>
                            <li class="flex items-start gap-2.5">
                                <MapPinIcon class="mt-0.5 h-4 w-4 shrink-0 text-primary-600" />
                                Mid City Hospital, Jail Road, Lahore
                            </li>
                            <li class="flex items-center gap-2.5">
                                <PhoneIcon class="h-4 w-4 shrink-0 text-primary-600" />
                                +92 300 1234567
                            </li>
                            <li class="flex items-center gap-2.5">
                                <ClockIcon class="h-4 w-4 shrink-0 text-primary-600" />
                                FMH Morning · Mid City Evening
                            </li>
                        </ul>
                        <Link :href="route('booking.create')" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-primary-700 transition hover:text-primary-800">
                            Book an appointment
                            <ArrowRightIcon class="h-4 w-4" />
                        </Link>
                    </div>
                </div>

                <div class="mt-12 flex flex-col items-center justify-between gap-3 border-t border-slate-100 pt-6 sm:flex-row">
                    <p class="text-xs text-slate-400">© {{ new Date().getFullYear() }} MediCare. All rights reserved.</p>
                    <p class="flex items-center gap-1.5 text-xs text-slate-400">
                        <ShieldCheckIcon class="h-4 w-4 text-primary-600" />
                        Secure &amp; confidential consultations
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
