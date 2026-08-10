<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StatCard from '@/Components/ui/StatCard.vue';
import Badge from '@/Components/ui/Badge.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import DataTable from '@/Components/ui/DataTable.vue';
import {
    CalendarDaysIcon,
    UsersIcon,
    UserGroupIcon,
    BanknotesIcon,
    ClockIcon,
    HomeIcon,
    UserCircleIcon,
    StarIcon,
    EnvelopeIcon,
    CheckIcon,
    BellIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    stats: { type: Object, required: true },
    statusCounts: { type: Object, default: () => ({}) },
    trend: { type: Array, default: () => [] },
    recentAppointments: { type: Array, default: () => [] },
    recentUsers: { type: Array, default: () => [] },
    recentReviews: { type: Array, default: () => [] },
    recentMessages: { type: Array, default: () => [] },
    doctor: { type: Object, default: null },
});

const navItems = [
    { label: 'Dashboard', route: 'admin.dashboard', icon: HomeIcon },
    { label: 'My Profile', route: 'profile.edit', icon: UserCircleIcon },
];

const statusTone = (status) =>
    ({ pending: 'amber', confirmed: 'primary', completed: 'green', cancelled: 'red' })[status] || 'slate';

const trendMax = Math.max(...props.trend.map((t) => t.appointments), 1);

const appointmentColumns = [
    { key: 'reference', label: 'Reference' },
    { key: 'patient', label: 'Patient' },
    { key: 'service', label: 'Service' },
    { key: 'date', label: 'Date' },
    { key: 'status', label: 'Status' },
    { key: 'amount', label: 'Fee', align: 'right' },
];

const messageColumns = [
    { key: 'sender', label: 'Sender' },
    { key: 'subject', label: 'Subject' },
    { key: 'received', label: 'Received' },
    { key: 'status', label: 'Status' },
    { key: 'action', label: '', align: 'right' },
];

const toggleMessage = (message) => {
    router.post(route('admin.messages.toggle', message.id), {}, {
        preserveScroll: true,
        preserveState: true,
    });
};
</script>

<template>
    <DashboardLayout :nav-items="navItems" title="Admin Dashboard">
        <Head title="Admin Dashboard" />

        <div>
            <h1 class="text-2xl font-extrabold text-navy-950">Overview</h1>
            <p class="mt-1 text-sm text-slate-500">A snapshot of your practice performance.</p>
        </div>

        <!-- Stats -->
        <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <StatCard :icon="CalendarDaysIcon" label="Total appointments" :value="stats.appointments" hint="All time" />
            <StatCard :icon="UsersIcon" label="Patients" :value="stats.patients" hint="Registered patients" tone="navy" />
            <StatCard :icon="UserGroupIcon" label="Doctors" :value="stats.doctors" hint="Active physicians" tone="sky" />
            <StatCard :icon="BanknotesIcon" label="Revenue" :value="'Rs ' + stats.revenue.toLocaleString()" hint="Collected payments" tone="emerald" />
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            <!-- Trend chart -->
            <div class="card p-6 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-navy-950">Appointments — last 7 days</h2>
                        <p class="mt-0.5 text-xs text-slate-400">Daily appointment volume</p>
                    </div>
                    <Badge tone="primary">{{ stats.month_revenue ? 'Rs ' + stats.month_revenue.toLocaleString() : 'Rs 0' }} this month</Badge>
                </div>

                <div class="mt-6 flex h-48 items-end gap-3 sm:gap-4">
                    <div v-for="point in trend" :key="point.label" class="group flex h-full flex-1 flex-col items-center justify-end gap-2">
                        <span class="text-xs font-bold text-navy-900 opacity-0 transition group-hover:opacity-100">{{ point.appointments }}</span>
                        <div
                            class="w-full max-w-[42px] rounded-t-lg bg-gradient-to-t from-primary-600 to-primary-400 transition-all duration-300 group-hover:from-primary-700 group-hover:to-primary-500"
                            :style="{ height: Math.max((point.appointments / trendMax) * 100, 4) + '%' }"
                            :title="`${point.label}: ${point.appointments} appointments`"
                        />
                        <span class="text-[11px] font-semibold uppercase text-slate-400">{{ point.label }}</span>
                    </div>
                </div>
            </div>

            <!-- Status breakdown -->
            <div class="card p-6">
                <h2 class="text-base font-bold text-navy-950">Appointment status</h2>
                <p class="mt-0.5 text-xs text-slate-400">Current distribution</p>
                <div class="mt-6 space-y-4">
                    <div v-for="(count, status) in statusCounts" :key="status">
                        <div class="flex items-center justify-between text-sm">
                            <Badge :tone="statusTone(status)">{{ status }}</Badge>
                            <span class="font-bold text-navy-900">{{ count }}</span>
                        </div>
                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="h-full rounded-full"
                                :class="{
                                    'bg-amber-400': status === 'pending',
                                    'bg-primary-500': status === 'confirmed',
                                    'bg-emerald-500': status === 'completed',
                                    'bg-red-400': status === 'cancelled',
                                }"
                                :style="{ width: (count / Math.max(Object.values(statusCounts).reduce((a, b) => a + b, 0), 1)) * 100 + '%' }"
                            />
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500">Pending payments</span>
                            <span class="font-bold text-amber-600">Rs {{ stats.pending_payments.toLocaleString() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent appointments -->
        <div class="mt-10">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-navy-950">Recent appointments</h2>
                <span class="text-xs font-medium text-slate-400">Latest bookings</span>
            </div>
            <div class="mt-4">
                <DataTable :columns="appointmentColumns" :rows="recentAppointments">
                    <template #cell-patient="{ row }">
                        <span class="font-semibold text-navy-900">{{ row.patient?.name }}</span>
                    </template>
                    <template #cell-service="{ row }">
                        <span class="text-slate-500">{{ row.service?.name }}</span>
                    </template>
                    <template #cell-date="{ row }">
                        <span class="text-slate-500">{{ row.date_label }} · {{ row.time_label }}</span>
                    </template>
                    <template #cell-status="{ row }">
                        <Badge :tone="statusTone(row.status)" size="xs">{{ row.status_label }}</Badge>
                    </template>
                    <template #cell-amount="{ row }">
                        <span class="font-bold text-navy-900">Rs {{ row.amount }}</span>
                    </template>
                </DataTable>
            </div>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            <!-- Recent users -->
            <div class="card p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-navy-950">Recent patients</h2>
                    <UsersIcon class="h-5 w-5 text-slate-300" />
                </div>
                <div class="mt-5 space-y-1">
                    <div v-for="user in recentUsers" :key="user.id" class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition hover:bg-slate-50">
                        <Avatar :name="user.name" size="sm" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-navy-900">{{ user.name }}</p>
                            <p class="truncate text-xs text-slate-400">{{ user.email }}</p>
                        </div>
                        <span class="text-xs text-slate-400">{{ user.joined }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent reviews -->
            <div class="card p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-navy-950">Recent reviews</h2>
                    <StarIcon class="h-5 w-5 text-amber-400" />
                </div>
                <div class="mt-5 space-y-1">
                    <div v-for="review in recentReviews" :key="review.id" class="rounded-xl px-2 py-3 transition hover:bg-slate-50">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-navy-900">{{ review.patient }}</p>
                            <span class="flex items-center gap-0.5">
                                <StarIcon v-for="i in 5" :key="i" class="h-3.5 w-3.5" :class="i <= review.rating ? 'text-amber-400' : 'text-slate-200'" />
                            </span>
                        </div>
                        <p class="mt-1 line-clamp-2 text-sm text-slate-500">“{{ review.comment }}”</p>
                        <p class="mt-1 text-[11px] text-slate-400">{{ review.date }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact messages -->
        <div class="mt-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <h2 class="text-lg font-bold text-navy-950">Contact messages</h2>
                    <Badge v-if="stats.unread_messages" tone="amber" dot>{{ stats.unread_messages }} new</Badge>
                </div>
                <EnvelopeIcon class="h-5 w-5 text-slate-300" />
            </div>
            <div class="mt-4">
                <DataTable :columns="messageColumns" :rows="recentMessages" empty-title="No messages yet" empty-message="Messages from the contact form will appear here.">
                    <template #cell-sender="{ row }">
                        <div class="flex items-center gap-3">
                            <span class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-50 text-xs font-bold text-primary-700 ring-1 ring-primary-100">
                                {{ row.initials }}
                                <span v-if="!row.is_read" class="absolute -right-0.5 -top-0.5 h-2.5 w-2.5 rounded-full bg-amber-400 ring-2 ring-white" />
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-navy-900">{{ row.name }}</p>
                                <p class="truncate text-xs text-slate-400">{{ row.email }}</p>
                            </div>
                        </div>
                    </template>
                    <template #cell-subject="{ row }">
                        <div class="min-w-0 max-w-xs">
                            <p class="truncate text-sm font-semibold" :class="row.is_read ? 'text-slate-600' : 'text-navy-950'">
                                {{ row.subject }}
                            </p>
                            <p class="mt-0.5 line-clamp-1 text-xs text-slate-400">“{{ row.message }}”</p>
                        </div>
                    </template>
                    <template #cell-received="{ row }">
                        <span class="whitespace-nowrap text-xs text-slate-500">{{ row.date_label }}</span>
                    </template>
                    <template #cell-status="{ row }">
                        <Badge :tone="row.is_read ? 'slate' : 'amber'" size="xs">{{ row.is_read ? 'Read' : 'New' }}</Badge>
                    </template>
                    <template #cell-action="{ row }">
                        <button
                            type="button"
                            @click="toggleMessage(row)"
                            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-primary-700 transition hover:bg-primary-50"
                        >
                            <CheckIcon v-if="!row.is_read" class="h-3.5 w-3.5" />
                            <BellIcon v-else class="h-3.5 w-3.5" />
                            {{ row.is_read ? 'Mark unread' : 'Mark read' }}
                        </button>
                    </template>
                </DataTable>
            </div>
        </div>
    </DashboardLayout>
</template>
