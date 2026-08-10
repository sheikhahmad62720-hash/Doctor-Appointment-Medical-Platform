<script setup>
import { Link } from '@inertiajs/vue3';
import ServiceIcon from './ServiceIcon.vue';
import Badge from './Badge.vue';
import Button from './Button.vue';
import { ArrowRightIcon, ClockIcon, VideoCameraIcon, BuildingOfficeIcon } from '@heroicons/vue/24/outline';

defineProps({
    service: { type: Object, required: true },
});
</script>

<template>
    <article class="card card-hover group flex flex-col p-6">
        <div class="flex items-start justify-between">
            <div
                class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-100 transition-transform duration-300 group-hover:scale-105"
            >
                <ServiceIcon :name="service.icon" class="h-6 w-6" />
            </div>
            <Badge :tone="service.consultation_type === 'online' ? 'sky' : service.consultation_type === 'physical' ? 'navy' : 'primary'" size="xs">
                <template v-if="service.consultation_type === 'online'">
                    <VideoCameraIcon class="h-3 w-3" /> Online
                </template>
                <template v-else-if="service.consultation_type === 'physical'">
                    <BuildingOfficeIcon class="h-3 w-3" /> In Clinic
                </template>
                <template v-else>
                    Online &amp; In Clinic
                </template>
            </Badge>
        </div>

        <h3 class="mt-5 text-lg font-bold text-navy-900">{{ service.name }}</h3>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">{{ service.description }}</p>

        <ul v-if="service.procedures?.length" class="mt-4 space-y-2">
            <li
                v-for="procedure in service.procedures"
                :key="procedure"
                class="flex items-center gap-2 text-sm text-slate-600"
            >
                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-primary-500" />
                {{ procedure }}
            </li>
        </ul>

        <div class="mt-5 flex items-center gap-4 text-sm">
            <span class="inline-flex items-center gap-1.5 text-slate-500">
                <ClockIcon class="h-4 w-4 text-primary-600" />
                {{ service.duration_label }}
            </span>
            <span class="text-lg font-bold text-navy-900">{{ service.price_label }}</span>
        </div>

        <div class="mt-5 flex items-center gap-3 border-t border-slate-100 pt-5">
            <Link :href="route('booking.create')" class="flex-1">
                <Button variant="secondary" size="sm" full-width>Book now</Button>
            </Link>
            <Link
                :href="route('services.show', service.slug)"
                class="inline-flex items-center gap-1 text-sm font-semibold text-slate-400 transition hover:text-primary-700"
            >
                Details
                <ArrowRightIcon class="h-4 w-4" />
            </Link>
        </div>
    </article>
</template>
