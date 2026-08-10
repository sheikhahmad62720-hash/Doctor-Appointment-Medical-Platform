<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Button from '@/Components/ui/Button.vue';
import Badge from '@/Components/ui/Badge.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import StatCard from '@/Components/ui/StatCard.vue';
import ConfirmDialog from '@/Components/ui/ConfirmDialog.vue';
import {
    CalendarDaysIcon,
    InboxIcon,
    ClockIcon,
    CheckCircleIcon,
    VideoCameraIcon,
    BuildingOfficeIcon,
    CheckIcon,
    XMarkIcon,
    PhoneIcon,
    EnvelopeIcon,
    HomeIcon,
    PlusIcon,
    UserCircleIcon,
    ClipboardDocumentListIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    greeting: { type: String, default: 'Good day' },
    stats: { type: Object, required: true },
    todaysAppointments: { type: Array, default: () => [] },
    pendingRequests: { type: Array, default: () => [] },
});

const page = usePage();
const doctor = page.props.auth?.user;

const navItems = [
    { label: 'Dashboard', route: 'doctor.dashboard', icon: HomeIcon },
    { label: 'My Profile', route: 'profile.edit', icon: UserCircleIcon },
];

const statusTone = (status) =>
    ({ pending: 'amber', confirmed: 'primary', completed: 'green', cancelled: 'red' })[status] || 'slate';

const actionTarget = ref(null);
const actionStatus = ref(null);
const processing = ref(false);

const updateStatus = (appointment, status) => {
    processing.value = true;
    router.post(
        route('appointments.status', appointment.id),
        { status },
        {
            onFinish: () => {
                processing.value = false;
                actionTarget.value = null;
            },
        },
    );
};

const requestAction = (appointment, status) => {
    if (status === 'cancelled') {
        actionTarget.value = appointment;
        actionStatus.value = 'cancelled';
    } else {
        updateStatus(appointment, status);
    }
};
</script>

<template>
    <DashboardLayout :nav-items="navItems" title="Doctor Dashboard">
        <Head title="Doctor Dashboard" />

        <!-- Header -->
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-navy-950">{{ greeting }}, {{ doctor?.name }}</h1>
                <p class="mt-1 text-sm text-slate-500">Here's your schedule and requests for today.</p>
            </div>
            <Link :href="route('home')">
                <Button variant="outline">View public site</Button>
            </Link>
        </div>

        <!-- Stats -->
        <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <StatCard :icon="CalendarDaysIcon" label="Today's appointments" :value="stats.today" hint="Scheduled for today" tone="primary" />
            <StatCard :icon="InboxIcon" label="Pending requests" :value="stats.pending" hint="Awaiting your response" tone="amber" />
            <StatCard :icon="ClockIcon" label="Available slots" :value="stats.available_slots" hint="Open for booking" tone="sky" />
            <StatCard :icon="CheckCircleIcon" label="Completed" :value="stats.completed" hint="All time" tone="emerald" />
        </div>

        <div class="mt-10 grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <!-- Today's schedule -->
            <div>
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-navy-950">Today's schedule</h2>
                    <span class="text-sm font-medium text-slate-400">{{ new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' }) }}</span>
                </div>

                <div v-if="todaysAppointments.length" class="mt-4 space-y-3">
                    <div v-for="appt in todaysAppointments" :key="appt.id" class="card p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-xl bg-primary-50 text-primary-700 ring-1 ring-primary-100">
                                    <span class="text-sm font-extrabold leading-none">{{ appt.time_label }}</span>
                                    <span class="mt-0.5 text-[9px] font-semibold uppercase text-primary-400">{{ appt.consultation_type }}</span>
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="font-semibold text-navy-900">{{ appt.patient?.name }}</p>
                                        <Badge :tone="statusTone(appt.status)" size="xs">{{ appt.status_label }}</Badge>
                                        <Badge :tone="appt.consultation_type === 'online' ? 'sky' : 'navy'" size="xs">
                                            <VideoCameraIcon v-if="appt.consultation_type === 'online'" class="h-3 w-3" />
                                            <BuildingOfficeIcon v-else class="h-3 w-3" />
                                            {{ appt.consultation_type === 'online' ? 'Online' : 'In Clinic' }}
                                        </Badge>
                                    </div>
                                    <p class="mt-0.5 text-sm text-slate-500">{{ appt.service?.name }}</p>
                                    <p v-if="appt.notes" class="mt-1.5 rounded-lg bg-slate-50 px-3 py-1.5 text-xs text-slate-500">“{{ appt.notes }}”</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 sm:shrink-0 sm:justify-end">
                                <template v-if="appt.status === 'pending'">
                                    <Button size="sm" @click="requestAction(appt, 'confirmed')"><CheckIcon class="h-4 w-4" /> Confirm</Button>
                                    <Button size="sm" variant="ghost" @click="requestAction(appt, 'cancelled')"><XMarkIcon class="h-4 w-4" /> Reject</Button>
                                </template>
                                <template v-else-if="appt.status === 'confirmed'">
                                    <a v-if="appt.consultation_type === 'online' && appt.meeting_url" :href="appt.meeting_url" target="_blank" rel="noopener">
                                        <Button size="sm"><VideoCameraIcon class="h-4 w-4" /> Start consultation</Button>
                                    </a>
                                    <Button size="sm" variant="success" @click="updateStatus(appt, 'completed')"><CheckCircleIcon class="h-4 w-4" /> Complete</Button>
                                    <Button size="sm" variant="ghost" @click="requestAction(appt, 'cancelled')"><XMarkIcon class="h-4 w-4" /> Cancel</Button>
                                </template>
                                <template v-else>
                                    <Badge :tone="appt.status === 'completed' ? 'green' : 'red'">{{ appt.status_label }}</Badge>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="card mt-4">
                    <div class="flex flex-col items-center px-6 py-14 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                            <CalendarDaysIcon class="h-7 w-7" />
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-navy-900">No appointments today</h3>
                        <p class="mt-1 max-w-sm text-sm text-slate-500">Enjoy a quiet day. New bookings will appear here automatically.</p>
                    </div>
                </div>
            </div>

            <!-- Pending requests -->
            <div>
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-navy-950">Pending requests</h2>
                    <span v-if="pendingRequests.length" class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-100 text-xs font-bold text-amber-700">{{ pendingRequests.length }}</span>
                </div>
                <p class="mt-1 text-sm text-slate-500">Appointments awaiting your confirmation.</p>

                <div v-if="pendingRequests.length" class="mt-4 space-y-3">
                    <div v-for="appt in pendingRequests" :key="appt.id" class="card p-4">
                        <div class="flex items-center gap-3">
                            <Avatar :name="appt.patient?.name" tone="sky" />
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-navy-900">{{ appt.patient?.name }}</p>
                                <p class="text-xs text-slate-400">{{ appt.service?.name }}</p>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
                            <span class="inline-flex items-center gap-1"><CalendarDaysIcon class="h-3.5 w-3.5 text-primary-600" />{{ appt.date_label }}</span>
                            <span class="inline-flex items-center gap-1"><ClockIcon class="h-3.5 w-3.5 text-primary-600" />{{ appt.time_label }}</span>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <Button size="sm" class="flex-1" @click="requestAction(appt, 'confirmed')"><CheckIcon class="h-4 w-4" /> Accept</Button>
                            <Button size="sm" variant="ghost" @click="requestAction(appt, 'cancelled')"><XMarkIcon class="h-4 w-4" /> Decline</Button>
                        </div>
                    </div>
                </div>

                <div v-else class="card mt-4">
                    <div class="flex flex-col items-center px-6 py-12 text-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                            <InboxIcon class="h-6 w-6" />
                        </div>
                        <h3 class="mt-3 text-sm font-semibold text-navy-900">All caught up</h3>
                        <p class="mt-1 text-xs text-slate-500">No pending requests right now.</p>
                    </div>
                </div>

                <!-- Contact quick actions -->
                <div class="card mt-6 p-5">
                    <h3 class="text-sm font-bold text-navy-900">Quick actions</h3>
                    <div class="mt-3 space-y-2">
                        <button class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                            <PhoneIcon class="h-4 w-4 text-primary-600" /> Call a patient
                        </button>
                        <button class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                            <EnvelopeIcon class="h-4 w-4 text-primary-600" /> Send reminders
                        </button>
                        <Link :href="route('booking.create')" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                            <PlusIcon class="h-4 w-4 text-primary-600" /> Open a slot manually
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmDialog
            :show="!!actionTarget"
            title="Reject this appointment?"
            :message="actionTarget ? `Reject the appointment for ${actionTarget.patient?.name} on ${actionTarget.date_label} at ${actionTarget.time_label}?` : ''"
            confirm-text="Reject appointment"
            :loading="processing"
            @confirm="updateStatus(actionTarget, actionStatus)"
            @close="actionTarget = null"
        />
    </DashboardLayout>
</template>
