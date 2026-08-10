<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import Brand from '@/Components/ui/Brand.vue';
import DoctorIllustration from '@/Components/ui/DoctorIllustration.vue';
import { ShieldCheckIcon, StarIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';

const page = usePage();
</script>

<template>
    <div class="flex min-h-screen bg-slate-50">
        <ToastContainer />

        <!-- Brand panel -->
        <div class="relative hidden w-[46%] flex-col justify-between overflow-hidden bg-navy-950 p-10 lg:flex xl:p-14">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-24 top-10 h-96 w-96 rounded-full bg-primary-600/20 blur-3xl" />
                <div class="absolute -bottom-32 -right-24 h-[420px] w-[420px] rounded-full bg-navy-700/50 blur-3xl" />
            </div>

            <div class="relative">
                <Link :href="route('home')"><Brand tone="dark" /></Link>
            </div>

            <div class="relative max-w-md">
                <div class="mx-auto -mb-4 w-64 opacity-90">
                    <DoctorIllustration />
                </div>
                <div class="relative z-10">
                    <h2 class="text-balance text-2xl font-extrabold leading-snug text-white">
                        "Healthcare that puts <span class="text-primary-400">you</span> first."
                    </h2>
                    <div class="mt-6 flex items-center gap-4">
                        <span class="flex items-center gap-1.5 rounded-full bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-white backdrop-blur">
                            <StarIcon class="h-4 w-4 text-amber-400" /> 4.9/5 rating
                        </span>
                        <span class="flex items-center gap-1.5 rounded-full bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-white backdrop-blur">
                            <ShieldCheckIcon class="h-4 w-4 text-primary-400" /> Secure &amp; private
                        </span>
                    </div>
                </div>
            </div>

            <div class="relative flex items-center gap-2 text-xs text-white/40">
                <CheckCircleIcon class="h-4 w-4 text-primary-400" />
                Secure patient portal
            </div>
        </div>

        <!-- Form panel -->
        <div class="flex flex-1 flex-col items-center justify-center px-4 py-12 sm:px-6 lg:px-10">
            <div class="w-full max-w-md">
                <div class="mb-8 flex justify-center lg:hidden">
                    <Link :href="route('home')"><Brand /></Link>
                </div>
                <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-card sm:p-8">
                    <slot />
                </div>
                <p class="mt-6 text-center text-sm text-slate-400">
                    <Link :href="route('home')" class="font-semibold text-primary-700 hover:text-primary-800">← Back to website</Link>
                </p>
            </div>
        </div>
    </div>
</template>
