<script setup>
import { Link, router, Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Button from '@/Components/ui/Button.vue';
import Badge from '@/Components/ui/Badge.vue';
import ServiceCard from '@/Components/ui/ServiceCard.vue';
import SectionHeading from '@/Components/ui/SectionHeading.vue';
import DoctorIllustration from '@/Components/ui/DoctorIllustration.vue';
import {
    ShieldCheckIcon,
    StarIcon,
    UserGroupIcon,
    CheckCircleIcon,
    ArrowRightIcon,
    CalendarDaysIcon,
    VideoCameraIcon,
    BuildingOfficeIcon,
    MapPinIcon,
    AcademicCapIcon,
    ClockIcon,
    PhoneIcon,
} from '@heroicons/vue/24/outline';

defineProps({
    doctor: { type: Object, default: null },
    services: { type: Array, default: () => [] },
    reviews: { type: Array, default: () => [] },
});

const goBook = () => router.visit(route('booking.create'));
</script>

<template>
    <PublicLayout :doctor="doctor">
        <Head title="Home" />

        <!-- ===================== HERO ===================== -->
        <section class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -right-40 -top-40 h-[480px] w-[480px] rounded-full bg-primary-100/60 blur-3xl" />
                <div class="absolute -left-32 top-64 h-[360px] w-[360px] rounded-full bg-navy-100/50 blur-3xl" />
            </div>

            <div class="container-px relative grid items-center gap-12 py-14 sm:py-20 lg:grid-cols-[1.05fr_0.95fr] lg:py-24">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 rounded-full border border-primary-100 bg-white/80 px-3.5 py-1.5 shadow-soft backdrop-blur">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-white">
                            <ShieldCheckIcon class="h-3 w-3" />
                        </span>
                        <span class="text-xs font-semibold text-primary-800">{{ doctor?.specialization }}</span>
                    </div>

                    <h1 class="mt-6 text-balance text-4xl font-extrabold leading-[1.1] tracking-tight text-navy-950 sm:text-5xl lg:text-[3.4rem]">
                        Expert surgical care,
                        <span class="text-primary-600">every step</span> of the way.
                    </h1>

                    <p class="mt-6 max-w-xl text-lg leading-relaxed text-slate-500">
                        Book a consultation with {{ doctor?.name }} — advanced bariatric, laparoscopic and
                        general surgery delivered with precision and compassion.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <Button size="lg" @click="goBook">
                            Book Appointment
                            <ArrowRightIcon class="h-5 w-5" />
                        </Button>
                        <Link :href="route('services')">
                            <Button variant="outline" size="lg" full-width>View Services</Button>
                        </Link>
                    </div>

                    <!-- Stats -->
                    <div class="mt-10 grid max-w-md grid-cols-3 gap-6 border-t border-slate-200/80 pt-7">
                        <div>
                            <p class="text-2xl font-extrabold text-navy-900">{{ doctor?.experience_years }}<span class="text-primary-600">+</span></p>
                            <p class="mt-0.5 text-xs font-medium uppercase tracking-wider text-slate-400">Years of experience</p>
                        </div>
                        <div>
                            <p class="text-2xl font-extrabold text-navy-900">8k<span class="text-primary-600">+</span></p>
                            <p class="mt-0.5 text-xs font-medium uppercase tracking-wider text-slate-400">Happy patients</p>
                        </div>
                        <div>
                            <p class="flex items-center gap-1 text-2xl font-extrabold text-navy-900">
                                {{ doctor?.rating ?? '4.9' }}
                                <StarIcon class="h-5 w-5 text-amber-400" />
                            </p>
                            <p class="mt-0.5 text-xs font-medium uppercase tracking-wider text-slate-400">Patient rating</p>
                        </div>
                    </div>
                </div>

                <!-- Doctor illustration + floating cards -->
                <div class="relative mx-auto w-full max-w-md lg:max-w-none">
                    <div class="relative">
                        <DoctorIllustration />

                        <!-- Floating: experience badge -->
                        <div class="absolute left-0 top-8 animate-fade-in-up rounded-2xl border border-slate-100 bg-white/95 p-3.5 shadow-lifted backdrop-blur sm:left-4" style="animation-delay: 150ms">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                                    <AcademicCapIcon class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-navy-900">{{ doctor?.experience_years }} Years</p>
                                    <p class="text-xs text-slate-400">Expert experience</p>
                                </div>
                            </div>
                        </div>

                        <!-- Floating: rating badge -->
                        <div class="absolute bottom-16 right-0 animate-fade-in-up rounded-2xl border border-slate-100 bg-white/95 p-3.5 shadow-lifted backdrop-blur sm:right-6" style="animation-delay: 300ms">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-0.5">
                                    <StarIcon v-for="i in 5" :key="i" class="h-4 w-4 text-amber-400" :class="i > 4 && 'text-amber-200'" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-navy-900">{{ doctor?.rating }}</p>
                                    <p class="text-xs text-slate-400">2,340 reviews</p>
                                </div>
                            </div>
                        </div>

                        <!-- Floating: availability badge -->
                        <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 animate-fade-in-up rounded-full border border-slate-100 bg-white/95 px-4 py-2 shadow-lifted backdrop-blur" style="animation-delay: 450ms">
                            <span class="inline-flex items-center gap-2 text-xs font-semibold text-navy-900">
                                <span class="relative flex h-2 w-2">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75" />
                                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500" />
                                </span>
                                Accepting new patients
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== SERVICES ===================== -->
        <section class="section-pad bg-white">
            <div class="container-px">
                <div class="flex flex-col items-start justify-between gap-6 lg:flex-row lg:items-end">
                    <SectionHeading
                        eyebrow="Our Services"
                        title="Comprehensive care, tailored to you"
                        description="From weight-loss surgery to advanced laparoscopic procedures — every treatment is personal, thorough and focused on your long-term health."
                    />
                    <Link :href="route('services')">
                        <Button variant="outline">View all services <ArrowRightIcon class="h-4 w-4" /></Button>
                    </Link>
                </div>

                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <ServiceCard v-for="service in services" :key="service.id" :service="service" />
                </div>
            </div>
        </section>

        <!-- ===================== HOW IT WORKS ===================== -->
        <section class="section-pad">
            <div class="container-px">
                <SectionHeading
                    align="center"
                    eyebrow="How it works"
                    title="Booking takes less than two minutes"
                    description="A simple, guided flow so you know exactly what to expect — from choosing a service to your consultation."
                />

                <div class="mt-14 grid gap-6 md:grid-cols-3">
                    <div v-for="(step, i) in [
                        { icon: CalendarDaysIcon, title: 'Choose a service', text: 'Pick the type of consultation you need and the doctor you want to see.' },
                        { icon: ClockIcon, title: 'Pick a date & time', text: 'See live availability and choose a slot that fits your schedule.' },
                        { icon: VideoCameraIcon, title: 'Meet your doctor', text: 'Join online or visit the clinic, then get clear next steps for your care.' },
                    ]" :key="i" class="relative">
                        <div class="card h-full p-7">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 text-white shadow-glow">
                                <component :is="step.icon" class="h-6 w-6" />
                            </div>
                            <span class="mt-6 block text-xs font-bold uppercase tracking-wider text-primary-600">Step {{ String(i + 1).padStart(2, '0') }}</span>
                            <h3 class="mt-2 text-lg font-bold text-navy-900">{{ step.title }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-500">{{ step.text }}</p>
                        </div>
                        <ArrowRightIcon v-if="i < 2" class="absolute -right-4 top-1/2 z-10 hidden h-5 w-5 -translate-y-1/2 text-slate-300 md:block" />
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== DOCTOR PROFILE ===================== -->
        <section class="section-pad bg-navy-950">
            <div class="container-px">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div class="relative mx-auto w-full max-w-md lg:max-w-none">
                        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-2">
                            <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-primary-900/60 to-navy-900">
                                <DoctorIllustration />
                            </div>
                            <div class="absolute bottom-6 left-1/2 flex -translate-x-1/2 items-center gap-2 rounded-full bg-white/95 px-4 py-2 shadow-lifted">
                                <ShieldCheckIcon class="h-4 w-4 text-primary-600" />
                                <span class="text-xs font-bold text-navy-900">Verified Physician</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <span class="section-eyebrow"><span class="h-1.5 w-1.5 rounded-full bg-primary-500" />Meet your doctor</span>
                        <h2 class="text-3xl font-bold text-white sm:text-4xl">{{ doctor?.name }}</h2>
                        <p class="mt-2 text-lg font-medium text-primary-300">{{ doctor?.specialization }}</p>

                        <p class="mt-6 leading-relaxed text-white/70">{{ doctor?.bio }}</p>

                        <div class="mt-8 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-white/40">Experience</p>
                                <p class="mt-1 text-lg font-bold text-white">{{ doctor?.experience_years }} years</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-white/40">Rating</p>
                                <p class="mt-1 flex items-center gap-1.5 text-lg font-bold text-white">
                                    {{ doctor?.rating }} <StarIcon class="h-4 w-4 text-amber-400" />
                                </p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-white/40">Consultation</p>
                                <p class="mt-1 text-lg font-bold text-white">from {{ doctor?.consultation_fee ? 'Rs ' + Number(doctor.consultation_fee).toLocaleString() : 'Rs 3,000' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-white/40">Location</p>
                                <p class="mt-1 flex items-center gap-1.5 text-lg font-bold text-white">
                                    <MapPinIcon class="h-4 w-4 text-primary-400" /> Lahore · 2 clinics
                                </p>
                            </div>
                        </div>

                        <div class="mt-8 space-y-3">
                            <p class="text-xs font-semibold uppercase tracking-wider text-white/50">Where to find me</p>
                            <div
                                v-for="clinic in (doctor?.clinics ?? [])"
                                :key="clinic.address"
                                class="flex items-center justify-between gap-4 rounded-2xl border border-white/10 bg-white/5 px-4 py-3"
                            >
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-white">{{ clinic.name }}</p>
                                    <p class="truncate text-xs text-white/50">{{ clinic.address }}</p>
                                </div>
                                <span class="shrink-0 rounded-full bg-primary-500/20 px-3 py-1 text-xs font-semibold text-primary-300">
                                    {{ clinic.timing }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-white/50">Qualifications</h3>
                            <ul class="mt-3 space-y-2.5">
                                <li v-for="q in (doctor?.qualifications ?? '').split('\n').filter(Boolean)" :key="q" class="flex items-start gap-2.5 text-sm text-white/80">
                                    <CheckCircleIcon class="mt-0.5 h-4 w-4 shrink-0 text-primary-400" />
                                    {{ q }}
                                </li>
                            </ul>
                        </div>

                        <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                            <Button size="lg" @click="goBook">
                                Book with {{ doctor?.name }}
                                <ArrowRightIcon class="h-5 w-5" />
                            </Button>
                            <Link :href="route('about')">
                                <Button variant="outline" size="lg" full-width>Learn more about me</Button>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== REVIEWS ===================== -->
        <section class="section-pad bg-white">
            <div class="container-px">
                <SectionHeading
                    align="center"
                    eyebrow="Patient stories"
                    title="Trusted by thousands of patients"
                    description="Real experiences from people who have received care at MediCare."
                />

                <div class="mt-12 grid gap-6 md:grid-cols-3">
                    <figure v-for="review in reviews" :key="review.id" class="card flex h-full flex-col p-6">
                        <div class="flex items-center gap-0.5">
                            <StarIcon v-for="i in 5" :key="i" class="h-4 w-4" :class="i <= review.rating ? 'text-amber-400' : 'text-slate-200'" />
                        </div>
                        <blockquote class="mt-4 flex-1 text-sm leading-relaxed text-slate-600">“{{ review.comment }}”</blockquote>
                        <figcaption class="mt-5 flex items-center gap-3 border-t border-slate-100 pt-4">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-50 text-xs font-bold text-primary-700 ring-1 ring-primary-100">
                                {{ review.patient.split(' ').slice(0, 2).map((w) => w[0]).join('') }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-navy-900">{{ review.patient }}</p>
                                <p class="text-xs text-slate-400">Verified patient · {{ review.date }}</p>
                            </div>
                        </figcaption>
                    </figure>
                </div>
            </div>
        </section>

        <!-- ===================== CTA ===================== -->
        <section class="container-px">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 to-primary-800 px-6 py-16 text-center shadow-lifted sm:px-16 sm:py-20">
                <div class="pointer-events-none absolute -left-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-2xl" />
                <div class="pointer-events-none absolute -bottom-24 -right-16 h-72 w-72 rounded-full bg-white/10 blur-2xl" />
                <div class="relative">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-white">
                        <PhoneIcon class="h-3.5 w-3.5" /> Same-week appointments available
                    </span>
                    <h2 class="mx-auto mt-6 max-w-2xl text-balance text-3xl font-extrabold text-white sm:text-4xl">
                        Your health deserves a doctor who listens.
                    </h2>
                    <p class="mx-auto mt-4 max-w-xl text-white/80">
                        Book your consultation today and take the first step toward feeling your best.
                    </p>
                    <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <Button size="lg" variant="secondary" class="!bg-white !text-primary-700 hover:!bg-primary-50" @click="goBook">
                            Book Appointment
                            <ArrowRightIcon class="h-5 w-5" />
                        </Button>
                        <Button size="lg" variant="outline" class="!border-white/30 !bg-white/10 !text-white hover:!bg-white/20" @click="goBook">
                            <VideoCameraIcon class="h-5 w-5" /> Request a Callback
                        </Button>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
