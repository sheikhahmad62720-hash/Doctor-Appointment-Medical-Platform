<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Button from '@/Components/ui/Button.vue';
import Badge from '@/Components/ui/Badge.vue';
import {
    CheckCircleIcon,
    CalendarDaysIcon,
    VideoCameraIcon,
    BuildingOfficeIcon,
    MapPinIcon,
    PhoneIcon,
    ArrowDownTrayIcon,
    ArrowRightIcon,
    CreditCardIcon,
    ClockIcon,
    UserIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    appointment: { type: Object, required: true },
    doctor: { type: Object, default: null },
});

const a = props.appointment;

const isOnline = a.consultation_type === 'online';

const googleCalendarUrl = computed(() => {
    const start = new Date(`${a.date}T${a.time}:00`);
    const end = new Date(start.getTime() + (a.service?.duration_minutes ?? 30) * 60000);
    const fmt = (d) =>
        d.toISOString().replace(/[-:]|\.\d{3}/g, '').slice(0, 15) + 'Z';
    const params = new URLSearchParams({
        action: 'TEMPLATE',
        text: `${a.service?.name} with ${a.doctor?.name}`,
        dates: `${fmt(start)}/${fmt(end)}`,
        details: `Appointment reference: ${a.reference}`,
        location: isOnline ? 'Online consultation' : a.doctor?.location ?? '',
    });
    return `https://calendar.google.com/calendar/render?${params.toString()}`;
});

const print = () => window.print();
</script>

<template>
    <PublicLayout :doctor="doctor">
        <Head title="Appointment Confirmed" />

        <section class="section-pad">
            <div class="container-px">
                <div class="mx-auto max-w-2xl">
                    <div class="card overflow-hidden">
                        <!-- Header -->
                        <div class="relative bg-gradient-to-br from-primary-600 to-primary-800 px-6 py-12 text-center text-white">
                            <div class="pointer-events-none absolute -left-16 -top-16 h-48 w-48 rounded-full bg-white/10 blur-2xl" />
                            <div class="pointer-events-none absolute -bottom-20 -right-10 h-56 w-56 rounded-full bg-white/10 blur-2xl" />

                            <div class="relative">
                                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white/15 ring-8 ring-white/10 backdrop-blur">
                                    <CheckCircleIcon class="h-10 w-10 text-white" />
                                </div>
                                <h1 class="mt-6 text-2xl font-extrabold sm:text-3xl">Appointment Confirmed!</h1>
                                <p class="mx-auto mt-2 max-w-md text-white/80">
                                    Your appointment has been booked successfully. A confirmation has been sent to your email.
                                </p>
                                <div class="mt-5 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-sm font-semibold backdrop-blur">
                                    Reference: <span class="font-extrabold tracking-wide">{{ a.reference }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="p-6 sm:p-8">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                                        <CalendarDaysIcon class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Date &amp; Time</p>
                                        <p class="mt-0.5 text-sm font-bold text-navy-900">{{ a.date_label }}</p>
                                        <p class="text-sm font-semibold text-primary-700">{{ a.time_label }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-navy-50 text-navy-600">
                                        <UserIcon />
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Doctor</p>
                                        <p class="mt-0.5 text-sm font-bold text-navy-900">{{ a.doctor?.name }}</p>
                                        <p class="text-sm text-slate-500">{{ a.doctor?.specialization }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                                        <VideoCameraIcon v-if="isOnline" class="h-5 w-5" />
                                        <BuildingOfficeIcon v-else class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Consultation</p>
                                        <p class="mt-0.5 text-sm font-bold text-navy-900">
                                            {{ isOnline ? 'Online consultation' : 'In-clinic visit' }}
                                        </p>
                                        <p class="text-sm text-slate-500">{{ a.service?.name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                        <CreditCardIcon class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Payment</p>
                                        <p class="mt-0.5 text-sm font-bold text-navy-900">
                                            Rs {{ Number(a.amount).toLocaleString() }} <Badge :tone="a.payment_status === 'paid' ? 'green' : 'amber'" size="xs">{{ a.payment_status === 'paid' ? 'Paid' : 'Pending' }}</Badge>
                                        </p>
                                        <p class="text-sm text-slate-500">Secure online payment</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Join / location -->
                            <div v-if="isOnline && a.meeting_url" class="mt-5 rounded-2xl border border-primary-100 bg-primary-50/60 p-5">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-bold text-primary-900">Your video consultation</p>
                                        <p class="mt-0.5 text-xs text-primary-700">Join the call at your scheduled time — the meeting link will be active 10 minutes before.</p>
                                    </div>
                                    <a :href="a.meeting_url" target="_blank" rel="noopener">
                                        <Button size="sm"><VideoCameraIcon class="h-4 w-4" /> Join consultation</Button>
                                    </a>
                                </div>
                            </div>

                            <div v-else class="mt-5 rounded-2xl border border-slate-200 bg-slate-50/60 p-5">
                                <div>
                                    <p class="text-sm font-bold text-navy-900">Clinic locations</p>
                                    <div class="mt-2 space-y-2">
                                        <div
                                            v-for="clinic in (a.doctor?.clinics ?? [])"
                                            :key="clinic.address"
                                            class="flex items-center justify-between gap-3 text-xs"
                                        >
                                            <p class="flex items-center gap-1.5 text-slate-500">
                                                <MapPinIcon class="h-3.5 w-3.5 shrink-0 text-primary-600" />
                                                {{ clinic.name }} — {{ clinic.address }}
                                            </p>
                                            <span class="shrink-0 rounded-full bg-primary-50 px-2.5 py-0.5 font-semibold text-primary-700 ring-1 ring-primary-100">
                                                {{ clinic.timing }}
                                            </span>
                                        </div>
                                    </div>
                                    <a :href="'tel:' + a.doctor?.phone" class="mt-3 inline-block">
                                        <Button variant="outline" size="sm"><PhoneIcon class="h-4 w-4" /> Call clinic</Button>
                                    </a>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-7 flex flex-col gap-2.5 sm:flex-row">
                                <a :href="googleCalendarUrl" target="_blank" rel="noopener" class="flex-1">
                                    <Button variant="outline" full-width>
                                        <CalendarDaysIcon class="h-4 w-4" /> Add to calendar
                                    </Button>
                                </a>
                                <Button variant="outline" class="flex-1" @click="print">
                                    <ArrowDownTrayIcon class="h-4 w-4" /> Download / Print
                                </Button>
                            </div>

                            <div class="mt-4 grid gap-2.5 sm:grid-cols-2">
                                <Link :href="route('dashboard')">
                                    <Button variant="secondary" full-width>
                                        Return to my dashboard <ArrowRightIcon class="h-4 w-4" />
                                    </Button>
                                </Link>
                                <Link :href="route('home')">
                                    <Button variant="ghost" full-width>Back to homepage</Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
