<script setup>
import { Link, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import ServiceCard from '@/Components/ui/ServiceCard.vue';
import SectionHeading from '@/Components/ui/SectionHeading.vue';
import Button from '@/Components/ui/Button.vue';
import { ArrowRightIcon, VideoCameraIcon, BuildingOfficeIcon } from '@heroicons/vue/24/outline';

defineProps({
    services: { type: Array, default: () => [] },
    doctor: { type: Object, default: null },
});
</script>

<template>
    <PublicLayout :doctor="doctor">
        <Head title="Services" />

        <section class="relative overflow-hidden bg-navy-950 py-20 text-center">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-24 top-0 h-80 w-80 rounded-full bg-primary-600/20 blur-3xl" />
                <div class="absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-navy-700/40 blur-3xl" />
            </div>
            <div class="container-px relative">
                <span class="section-eyebrow"><span class="h-1.5 w-1.5 rounded-full bg-primary-500" />Our Services</span>
                <h1 class="mx-auto max-w-3xl text-balance text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                    Care designed around your needs
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-lg leading-relaxed text-white/70">
                    Every service is delivered personally by Dr. {{ doctor?.name?.replace('Dr. ', '') }} — online or in clinic.
                </p>
            </div>
        </section>

        <section class="section-pad">
            <div class="container-px">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <ServiceCard v-for="service in services" :key="service.id" :service="service" />
                </div>
            </div>
        </section>

        <!-- Consultation types -->
        <section class="section-pad bg-white">
            <div class="container-px">
                <SectionHeading
                    align="center"
                    eyebrow="Ways to consult"
                    title="Online or in clinic — your choice"
                    description="Choose the format that works best for you. Both options are secure, personal and confidential."
                />
                <div class="mt-12 grid gap-6 md:grid-cols-2">
                    <div class="card card-hover p-8">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 ring-1 ring-sky-100">
                            <VideoCameraIcon class="h-6 w-6" />
                        </div>
                        <h3 class="mt-5 text-xl font-bold text-navy-900">Online Consultation</h3>
                        <ul class="mt-4 space-y-2.5 text-sm text-slate-600">
                            <li class="flex items-center gap-2.5"><span class="h-1.5 w-1.5 rounded-full bg-sky-500" /> Secure HD video call</li>
                            <li class="flex items-center gap-2.5"><span class="h-1.5 w-1.5 rounded-full bg-sky-500" /> E-prescriptions &amp; referrals</li>
                            <li class="flex items-center gap-2.5"><span class="h-1.5 w-1.5 rounded-full bg-sky-500" /> Ideal for follow-ups &amp; quick advice</li>
                            <li class="flex items-center gap-2.5"><span class="h-1.5 w-1.5 rounded-full bg-sky-500" /> Join from anywhere with one click</li>
                        </ul>
                        <Link :href="route('booking.create')" class="mt-6 inline-block">
                            <Button>Book online <ArrowRightIcon class="h-4 w-4" /></Button>
                        </Link>
                    </div>
                    <div class="card card-hover p-8">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-navy-50 text-navy-600 ring-1 ring-navy-100">
                            <BuildingOfficeIcon class="h-6 w-6" />
                        </div>
                        <h3 class="mt-5 text-xl font-bold text-navy-900">In-Clinic Visit</h3>
                        <ul class="mt-4 space-y-2.5 text-sm text-slate-600">
                            <li class="flex items-center gap-2.5"><span class="h-1.5 w-1.5 rounded-full bg-navy-500" /> Physical examination</li>
                            <li class="flex items-center gap-2.5"><span class="h-1.5 w-1.5 rounded-full bg-navy-500" /> On-site diagnostics</li>
                            <li class="flex items-center gap-2.5"><span class="h-1.5 w-1.5 rounded-full bg-navy-500" /> Same-day test results</li>
                            <li class="flex items-center gap-2.5"><span class="h-1.5 w-1.5 rounded-full bg-navy-500" /> Accessible clinic location</li>
                        </ul>
                        <Link :href="route('booking.create')" class="mt-6 inline-block">
                            <Button variant="secondary">Book a visit <ArrowRightIcon class="h-4 w-4" /></Button>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <section class="container-px">
            <div class="flex flex-col items-center justify-between gap-6 rounded-3xl bg-navy-950 p-8 text-center sm:p-10 lg:flex-row lg:text-left">
                <div class="max-w-xl">
                    <h2 class="text-2xl font-bold text-white">Not sure which service you need?</h2>
                    <p class="mt-2 text-white/60">Book a general consultation and Dr. {{ doctor?.name?.replace('Dr. ', '') }} will guide you from there.</p>
                </div>
                <Link :href="route('booking.create')">
                    <Button size="lg">Book Appointment <ArrowRightIcon class="h-5 w-5" /></Button>
                </Link>
            </div>
        </section>
    </PublicLayout>
</template>
