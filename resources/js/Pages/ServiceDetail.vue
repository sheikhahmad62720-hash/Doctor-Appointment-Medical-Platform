<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import ServiceIcon from '@/Components/ui/ServiceIcon.vue';
import ServiceCard from '@/Components/ui/ServiceCard.vue';
import Badge from '@/Components/ui/Badge.vue';
import Button from '@/Components/ui/Button.vue';
import SectionHeading from '@/Components/ui/SectionHeading.vue';
import site from '@/config/site.js';
import {
    ArrowLeftIcon,
    ArrowRightIcon,
    CheckIcon,
    ClockIcon,
    BuildingOfficeIcon,
    MapPinIcon,
    ShieldCheckIcon,
    StarIcon,
    AcademicCapIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    slug: { type: String, default: '' },
});

const doctor = site.doctor;
const service = computed(() => site.services.find((s) => s.slug === props.slug) || null);
const related = computed(() => site.services.filter((s) => s.slug !== props.slug));
</script>

<template>
    <PublicLayout>
        <Head :title="service?.name" />

        <section class="relative overflow-hidden bg-navy-950 py-16 sm:py-20">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-24 top-0 h-80 w-80 rounded-full bg-primary-600/20 blur-3xl" />
                <div class="absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-navy-700/40 blur-3xl" />
            </div>

            <div class="container-px relative">
                <Link
                    :href="route('services')"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-white/60 transition hover:text-white"
                >
                    <ArrowLeftIcon class="h-4 w-4" />
                    All services
                </Link>

                <div class="mt-8 grid items-center gap-8 lg:grid-cols-[auto_1fr]">
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-white/10 text-primary-300 ring-1 ring-white/15 backdrop-blur">
                        <ServiceIcon :name="service?.icon" class="h-10 w-10" />
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2.5">
                            <Badge :tone="service?.consultation_type === 'online' ? 'sky' : 'navy'">
                                <BuildingOfficeIcon v-if="service?.consultation_type === 'physical'" class="h-3 w-3" />
                                <template v-if="service?.consultation_type === 'physical'"> In Clinic</template>
                                <template v-else-if="service?.consultation_type === 'online'"> Online</template>
                                <template v-else> Online &amp; In Clinic</template>
                            </Badge>
                            <Badge tone="slate"><ClockIcon class="h-3 w-3" /> {{ service?.duration_label }}</Badge>
                            <Badge tone="primary">Rs {{ Number(service?.price ?? 0).toLocaleString() }}</Badge>
                        </div>
                        <h1 class="mt-4 text-balance text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                            {{ service?.name }}
                        </h1>
                        <p class="mt-3 max-w-2xl text-base leading-relaxed text-white/70">
                            {{ service?.description }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-pad">
            <div class="container-px grid items-start gap-10 lg:grid-cols-[1fr_360px]">
                <div class="space-y-10">
                    <div class="card p-8">
                        <h2 class="text-xl font-bold text-navy-950">About this service</h2>
                        <p class="mt-3 leading-relaxed text-slate-600">
                            {{ service?.description }} Dr. {{ doctor?.name?.replace('Dr. ', '') }}
                            performs this procedure personally, using modern, minimally invasive techniques
                            to ensure faster recovery, minimal scarring and excellent outcomes.
                        </p>

                        <div v-if="service?.procedures?.length" class="mt-7">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Procedures we cover</h3>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div
                                    v-for="procedure in service?.procedures"
                                    :key="procedure"
                                    class="flex items-start gap-2.5 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5"
                                >
                                    <CheckIcon class="mt-0.5 h-5 w-5 shrink-0 text-primary-600" />
                                    <span class="text-sm font-medium text-navy-900">{{ procedure }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card p-8">
                        <h2 class="text-xl font-bold text-navy-950">What to expect</h2>
                        <div class="mt-6 grid gap-6 sm:grid-cols-3">
                            <div>
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                                    <AcademicCapIcon class="h-5 w-5" />
                                </div>
                                <h4 class="mt-4 text-sm font-bold text-navy-900">Expert consultation</h4>
                                <p class="mt-1.5 text-sm leading-relaxed text-slate-500">
                                    A thorough evaluation by Dr. {{ doctor?.name?.replace('Dr. ', '') }}
                                    with clear explanations of your options.
                                </p>
                            </div>
                            <div>
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                                    <ClockIcon class="h-5 w-5" />
                                </div>
                                <h4 class="mt-4 text-sm font-bold text-navy-900">Personalised plan</h4>
                                <p class="mt-1.5 text-sm leading-relaxed text-slate-500">
                                    A tailored treatment plan you can understand, prepared for your specific needs.
                                </p>
                            </div>
                            <div>
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                                    <ShieldCheckIcon class="h-5 w-5" />
                                </div>
                                <h4 class="mt-4 text-sm font-bold text-navy-900">Caring follow-up</h4>
                                <p class="mt-1.5 text-sm leading-relaxed text-slate-500">
                                    Guidance before and after your procedure, every step of the way.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-8">
                        <h2 class="text-xl font-bold text-navy-950">Where you'll be seen</h2>
                        <div class="mt-5 space-y-3">
                            <div
                                v-for="clinic in doctor?.clinics"
                                :key="clinic.name"
                                class="flex items-start gap-3 rounded-xl border border-slate-100 bg-slate-50/60 p-4"
                            >
                                <MapPinIcon class="mt-0.5 h-5 w-5 shrink-0 text-primary-600" />
                                <div>
                                    <p class="text-sm font-bold text-navy-900">{{ clinic.name }}</p>
                                    <p class="mt-0.5 text-sm text-slate-500">{{ clinic.address }} — {{ clinic.timing }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="lg:sticky lg:top-24">
                    <div class="card rounded-2xl p-6 shadow-lifted">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary-600 text-sm font-bold text-white">
                                {{ doctor?.initials }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-navy-950">{{ doctor?.name }}</p>
                                <p class="mt-0.5 flex items-center gap-1 text-xs text-slate-500">
                                    <StarIcon class="h-3.5 w-3.5 text-amber-400" /> {{ doctor?.rating }} · {{ doctor?.experience_years }} yrs exp
                                </p>
                            </div>
                        </div>

                        <dl class="mt-5 space-y-3.5 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500">Consultation fee</dt>
                                <dd class="font-bold text-navy-950">{{ service?.price_label }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500">Duration</dt>
                                <dd class="font-semibold text-navy-950">{{ service?.duration_label }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-500">Format</dt>
                                <dd class="font-semibold text-navy-950">{{ service?.consultation_type_label }}</dd>
                            </div>
                        </dl>

                        <Link :href="route('booking.create')" class="mt-6 block">
                            <Button size="lg" full-width>
                                Book this service <ArrowRightIcon class="h-4 w-4" />
                            </Button>
                        </Link>
                        <p class="mt-3 flex items-center justify-center gap-1.5 text-xs text-slate-400">
                            <ShieldCheckIcon class="h-3.5 w-3.5 text-primary-600" />
                            Secure &amp; confidential booking
                        </p>
                    </div>
                </aside>
            </div>
        </section>

        <section v-if="related?.length" class="section-pad bg-white">
            <div class="container-px">
                <SectionHeading
                    align="center"
                    eyebrow="More services"
                    title="Explore other treatments"
                    description="A complete range of bariatric, laparoscopic and general surgical care under one roof."
                />
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <ServiceCard v-for="service in related" :key="service.id" :service="service" />
                </div>
            </div>
        </section>

        <section class="container-px">
            <div class="flex flex-col items-center justify-between gap-6 rounded-3xl bg-navy-950 p-8 text-center sm:p-10 lg:flex-row lg:text-left">
                <div class="max-w-xl">
                    <h2 class="text-2xl font-bold text-white">Ready to get started?</h2>
                    <p class="mt-2 text-white/60">
                        Book your appointment with Dr. {{ doctor?.name?.replace('Dr. ', '') }} in just a few minutes.
                    </p>
                </div>
                <Link :href="route('booking.create')">
                    <Button size="lg">Book Appointment <ArrowRightIcon class="h-5 w-5" /></Button>
                </Link>
            </div>
        </section>
    </PublicLayout>
</template>
