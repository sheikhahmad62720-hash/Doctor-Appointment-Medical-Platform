<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Button from '@/Components/ui/Button.vue';
import Badge from '@/Components/ui/Badge.vue';
import StatCard from '@/Components/ui/StatCard.vue';
import ConfirmDialog from '@/Components/ui/ConfirmDialog.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import { toast } from '@/lib/toast';
import {
    CalendarDaysIcon,
    ClipboardDocumentListIcon,
    CheckCircleIcon,
    CreditCardIcon,
    ArrowRightIcon,
    VideoCameraIcon,
    BuildingOfficeIcon,
    ClockIcon,
    MapPinIcon,
    PlusIcon,
    HomeIcon,
    UserCircleIcon,
    ChatBubbleLeftRightIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    stats: { type: Object, required: true },
    upcoming: { type: Object, default: null },
    appointments: { type: Array, default: () => [] },
});

const navItems = [
    { label: 'Dashboard', route: 'dashboard', icon: HomeIcon },
    { label: 'Book Appointment', route: 'booking.create', icon: PlusIcon },
    { label: 'Messages', route: 'chat.index', icon: ChatBubbleLeftRightIcon },
    { label: 'My Profile', route: 'profile.edit', icon: UserCircleIcon },
];

const statusTone = (status) =>
    ({ pending: 'amber', confirmed: 'primary', completed: 'green', cancelled: 'red' })[status] || 'slate';

const cancelTarget = ref(null);
const cancelling = ref(false);

const confirmCancel = () => {
    cancelling.value = true;
    router.post(
        route('appointments.cancel', cancelTarget.value.id),
        {},
        {
            onSuccess: () => toast('Appointment cancelled', 'success'),
            onFinish: () => {
                cancelling.value = false;
                cancelTarget.value = null;
            },
        },
    );
};
</script>

<template>
    <DashboardLayout :nav-items="navItems" title="Dashboard">
        <Head title="Dashboard" />

        <!-- Header -->
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-extrabold text-navy-950">Welcome back</h1>
                <p class="mt-1 text-sm text-slate-500">Here's what's happening with your health care.</p>
            </div>
            <Link :href="route('booking.create')">
                <Button><PlusIcon class="h-4 w-4" /> Book Appointment</Button>
            </Link>
        </div>

        <!-- Stats -->
        <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <StatCard :icon="CalendarDaysIcon" label="Upcoming" :value="stats.upcoming" hint="Pending & confirmed" />
            <StatCard :icon="ClipboardDocumentListIcon" label="Total appointments" :value="stats.total" hint="All time" />
            <StatCard :icon="CheckCircleIcon" label="Completed visits" :value="stats.completed" hint="Consultations done" />
            <StatCard :icon="CreditCardIcon" label="Pending payments" :value="stats.pending_payments" hint="Awaiting payment" />
        </div>

        <!-- Upcoming appointment -->
        <div class="mt-8">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-navy-950">Upcoming appointment</h2>
                <Link :href="route('booking.create')" class="text-sm font-semibold text-primary-700 hover:text-primary-800">Book another</Link>
            </div>

            <div v-if="upcoming" class="mt-4 overflow-hidden rounded-2xl border border-primary-100 bg-gradient-to-br from-primary-50 to-white p-6 shadow-soft sm:p-8">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto]">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
                        <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-primary-600 text-2xl font-extrabold text-white shadow-glow">
                            {{ upcoming.doctor?.initials }}
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <Badge :tone="statusTone(upcoming.status)">{{ upcoming.status_label }}</Badge>
                                <Badge :tone="upcoming.consultation_type === 'online' ? 'sky' : 'navy'" size="xs">
                                    <VideoCameraIcon v-if="upcoming.consultation_type === 'online'" class="h-3 w-3" />
                                    <BuildingOfficeIcon v-else class="h-3 w-3" />
                                    {{ upcoming.consultation_type === 'online' ? 'Online' : 'In Clinic' }}
                                </Badge>
                            </div>
                            <h3 class="mt-3 text-xl font-bold text-navy-950">{{ upcoming.doctor?.name }}</h3>
                            <p class="text-sm text-slate-500">{{ upcoming.service?.name }} · {{ upcoming.doctor?.specialization }}</p>
                            <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-600">
                                <span class="inline-flex items-center gap-1.5">
                                    <CalendarDaysIcon class="h-4 w-4 text-primary-600" />
                                    {{ upcoming.date_label }}, {{ upcoming.time_label }}
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <ClockIcon class="h-4 w-4 text-primary-600" />
                                    {{ upcoming.service?.duration_minutes }} minutes
                                </span>
                                <span class="inline-flex items-center gap-1.5">
                                    <MapPinIcon class="h-4 w-4 text-primary-600" />
                                    {{ upcoming.consultation_type === 'online' ? 'Video call' : upcoming.doctor?.location }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5 sm:flex-row lg:flex-col lg:justify-center">
                        <a v-if="upcoming.consultation_type === 'online' && upcoming.meeting_url" :href="upcoming.meeting_url" target="_blank" rel="noopener">
                            <Button size="lg"><VideoCameraIcon class="h-4 w-4" /> Join consultation</Button>
                        </a>
                        <Button v-else variant="outline" size="lg"><MapPinIcon class="h-4 w-4" /> View directions</Button>
                        <Button v-if="upcoming.cancellable" variant="ghost" size="lg" @click="cancelTarget = upcoming">Cancel appointment</Button>
                    </div>
                </div>
            </div>

            <div v-else class="card mt-4">
                <EmptyState
                    icon="calendar"
                    title="No upcoming appointments"
                    message="When you book a consultation, it will appear here so you can manage it easily."
                >
                    <template #action>Book an appointment</template>
                </EmptyState>
            </div>
        </div>

        <!-- Appointment history -->
        <div class="mt-10">
            <h2 class="text-lg font-bold text-navy-950">Appointment history</h2>
            <p class="mt-1 text-sm text-slate-500">All your past and upcoming consultations.</p>

            <div v-if="appointments.length" class="mt-4 space-y-3">
                <div v-for="appt in appointments" :key="appt.id" class="card flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-xl bg-navy-50 text-navy-800 ring-1 ring-navy-100">
                            <span class="text-sm font-extrabold leading-none">{{ new Date(appt.date).getDate() }}</span>
                            <span class="text-[10px] font-semibold uppercase">{{ new Date(appt.date).toLocaleDateString('en-US', { month: 'short' }) }}</span>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-semibold text-navy-900">{{ appt.service?.name }}</p>
                                <Badge :tone="statusTone(appt.status)" size="xs">{{ appt.status_label }}</Badge>
                            </div>
                            <p class="mt-0.5 text-sm text-slate-500">
                                {{ appt.doctor?.name }} · {{ appt.time_label }} · {{ appt.consultation_type === 'online' ? 'Online' : 'In Clinic' }}
                            </p>
                            <p class="mt-0.5 text-xs text-slate-400">Ref: {{ appt.reference }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:shrink-0">
                        <Link :href="route('appointments.show', appt.id)">
                            <Button variant="ghost" size="sm">Details</Button>
                        </Link>
                        <Button v-if="appt.cancellable" variant="danger" size="sm" @click="cancelTarget = appt">Cancel</Button>
                    </div>
                </div>
            </div>
            <div v-else class="card mt-4">
                <EmptyState
                    icon="clipboard"
                    title="No appointments yet"
                    message="Your complete booking history will appear here."
                />
            </div>
        </div>

        <ConfirmDialog
            :show="!!cancelTarget"
            title="Cancel this appointment?"
            :message="cancelTarget ? `Your appointment on ${cancelTarget.date_label} at ${cancelTarget.time_label} will be cancelled. Any payment will be refunded.` : ''"
            confirm-text="Cancel appointment"
            :loading="cancelling"
            @confirm="confirmCancel"
            @close="cancelTarget = null"
        />
    </DashboardLayout>
</template>
