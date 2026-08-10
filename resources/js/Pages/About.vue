<script setup>
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Button from '@/Components/ui/Button.vue';
import SectionHeading from '@/Components/ui/SectionHeading.vue';
import {
    ShieldCheckIcon,
    StarIcon,
    CheckCircleIcon,
    HeartIcon,
    UserGroupIcon,
    ClipboardDocumentCheckIcon,
    AcademicCapIcon,
    ArrowRightIcon,
} from '@heroicons/vue/24/outline';

defineProps({
    doctor: { type: Object, default: null },
});

const values = [
    { icon: HeartIcon, title: 'Patient-first care', text: 'Every decision starts with what is best for you — not what is most convenient.' },
    { icon: ClipboardDocumentCheckIcon, title: 'Evidence-based medicine', text: 'Treatments grounded in the latest medical research and clinical guidelines.' },
    { icon: UserGroupIcon, title: 'Whole-person approach', text: 'We look beyond symptoms to understand your lifestyle, history and goals.' },
    { icon: ShieldCheckIcon, title: 'Trust & privacy', text: 'Your health information is protected and never shared without consent.' },
];
</script>

<template>
    <PublicLayout :doctor="doctor">
        <Head title="About" />

        <!-- Page hero -->
        <section class="relative overflow-hidden bg-navy-950 py-20 text-center">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-24 top-0 h-80 w-80 rounded-full bg-primary-600/20 blur-3xl" />
                <div class="absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-navy-700/40 blur-3xl" />
            </div>
            <div class="container-px relative">
                <span class="section-eyebrow"><span class="h-1.5 w-1.5 rounded-full bg-primary-500" />About MediCare</span>
                <h1 class="mx-auto max-w-3xl text-balance text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                    Expert surgery, delivered with a personal touch
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-lg leading-relaxed text-white/70">
                    A practice built around one idea — that surgical care should be
                    clear, accessible and genuinely caring.
                </p>
            </div>
        </section>

        <!-- Doctor story -->
        <section class="section-pad bg-white">
            <div class="container-px grid items-center gap-12 lg:grid-cols-2">
                <div class="relative mx-auto w-full max-w-md lg:max-w-none">
                    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-slate-50 p-2 shadow-lifted">
                        <img src="DOCTOR_IMAGE_URL" alt="Portrait of Dr. Awais Malik" class="h-auto w-full" />
                    </div>
                    <div class="absolute -bottom-4 left-1/2 flex -translate-x-1/2 items-center gap-2 rounded-full bg-white px-4 py-2 shadow-lifted ring-1 ring-slate-100">
                        <StarIcon class="h-4 w-4 text-amber-400" />
                        <span class="text-xs font-bold text-navy-900">{{ doctor?.rating }} patient rating</span>
                    </div>
                </div>

                <div>
                    <SectionHeading
                        eyebrow="Meet your doctor"
                        title="{{ doctor?.name }}"
                    />
                    <p class="mt-2 text-lg font-medium text-primary-700">{{ doctor?.specialization }}</p>
                    <div class="mt-5 space-y-4 leading-relaxed text-slate-600">
                        <p>{{ doctor?.bio }}</p>
                        <p>
                            As Professor of Surgery at Fatima Memorial Hospital, he has performed thousands of
                            successful procedures over {{ doctor?.experience_years }}+ years — from life-changing
                            bariatric (weight loss) surgery to advanced laparoscopic and general surgery.
                            He believes that a strong doctor–patient relationship is the foundation of effective care.
                        </p>
                        <p>
                            When you book with MediCare, you get unhurried consultations, clear explanations and a
                            personalised treatment plan you can actually understand.
                        </p>
                    </div>

                    <div class="mt-7">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Qualifications</h3>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div v-for="(q, i) in (doctor?.qualifications ?? '').split('\n').filter(Boolean)" :key="i" class="flex items-start gap-2.5 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5">
                                <AcademicCapIcon class="mt-0.5 h-5 w-5 shrink-0 text-primary-600" />
                                <span class="text-sm font-medium text-navy-900">{{ q }}</span>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-500">Professor of Surgery at <span class="font-semibold text-navy-900">{{ doctor?.education }}</span></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values -->
        <section class="section-pad">
            <div class="container-px">
                <SectionHeading
                    align="center"
                    eyebrow="Our values"
                    title="What we stand for"
                    description="The principles that guide every consultation, every recommendation and every interaction."
                />
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="value in values" :key="value.title" class="card card-hover p-6 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                            <component :is="value.icon" class="h-6 w-6" />
                        </div>
                        <h3 class="mt-5 text-base font-bold text-navy-900">{{ value.title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500">{{ value.text }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="bg-white py-16">
            <div class="container-px">
                <div class="grid gap-8 rounded-3xl border border-slate-100 bg-gradient-to-br from-slate-50 to-primary-50/50 p-8 sm:grid-cols-2 lg:grid-cols-4 lg:p-12">
                    <div class="text-center">
                        <p class="text-4xl font-extrabold text-primary-700">{{ doctor?.experience_years }}<span>+</span></p>
                        <p class="mt-1 text-sm font-medium text-slate-500">Years in practice</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-extrabold text-primary-700">8k<span>+</span></p>
                        <p class="mt-1 text-sm font-medium text-slate-500">Patients treated</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-extrabold text-primary-700">{{ doctor?.rating }}</p>
                        <p class="mt-1 text-sm font-medium text-slate-500">Average rating</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-extrabold text-primary-700">97<span>%</span></p>
                        <p class="mt-1 text-sm font-medium text-slate-500">Patient satisfaction</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="container-px">
            <div class="flex flex-col items-center justify-between gap-6 rounded-3xl border border-primary-100 bg-primary-50/60 p-8 sm:p-10 lg:flex-row">
                <div class="max-w-xl">
                    <h2 class="text-2xl font-bold text-navy-900">Ready to meet {{ doctor?.name }}?</h2>
                    <p class="mt-2 text-slate-500">Choose a service and book your consultation in under two minutes.</p>
                </div>
                <Link :href="route('booking.create')">
                    <Button size="lg">
                        Book an appointment
                        <ArrowRightIcon class="h-5 w-5" />
                    </Button>
                </Link>
            </div>
        </section>
    </PublicLayout>
</template>
