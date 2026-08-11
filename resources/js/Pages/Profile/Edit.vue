<script setup>
import { usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { HomeIcon, UserCircleIcon, PlusIcon } from '@heroicons/vue/24/outline';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const page = usePage();
const user = page.props.auth?.user;

const homeRoute = user?.role === 'admin' ? 'admin.dashboard' : 'dashboard';

const navItems = [
    { label: 'Dashboard', route: homeRoute, icon: HomeIcon },
    { label: 'Book Appointment', route: 'booking.create', icon: PlusIcon },
    { label: 'My Profile', route: 'profile.edit', icon: UserCircleIcon },
];
</script>

<template>
    <Head title="Profile" />

    <DashboardLayout :nav-items="navItems" title="Profile">
        <div>
            <h1 class="text-2xl font-extrabold text-navy-950">My profile</h1>
            <p class="mt-1 text-sm text-slate-500">Manage your account information and security.</p>
        </div>

        <div class="mt-8 max-w-3xl space-y-6">
            <div class="card p-6 sm:p-8">
                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
            </div>
            <div class="card p-6 sm:p-8">
                <UpdatePasswordForm />
            </div>
            <div class="card p-6 sm:p-8">
                <DeleteUserForm />
            </div>
        </div>
    </DashboardLayout>
</template>
