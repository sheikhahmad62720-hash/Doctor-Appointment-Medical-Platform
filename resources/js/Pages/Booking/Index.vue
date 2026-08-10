<script setup>
import { computed, reactive, ref, watch } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Button from '@/Components/ui/Button.vue';
import Label from '@/Components/ui/Label.vue';
import Input from '@/Components/ui/Input.vue';
import Textarea from '@/Components/ui/Textarea.vue';
import FieldError from '@/Components/ui/FieldError.vue';
import ProgressSteps from '@/Components/ui/ProgressSteps.vue';
import ServiceIcon from '@/Components/ui/ServiceIcon.vue';
import {
    ArrowLeftIcon,
    ArrowRightIcon,
    CheckIcon,
    ClockIcon,
    VideoCameraIcon,
    BuildingOfficeIcon,
    CalendarDaysIcon,
    MapPinIcon,
    ShieldCheckIcon,
    CreditCardIcon,
    LockClosedIcon,
} from '@heroicons/vue/24/outline';
import axios from 'axios';

const props = defineProps({
    doctor: { type: Object, default: null },
    services: { type: Array, default: () => [] },
    todaySlots: { type: Object, default: () => ({ slots: [] }) },
});

const page = usePage();

const steps = ['Service', 'Consultation Type', 'Date & Time', 'Patient Details', 'Payment'];
const current = ref(0);

const serviceId = ref(null);
const consultationType = ref(null);
const date = ref(new Date().toISOString().slice(0, 10));
const time = ref(null);
const slots = ref(props.todaySlots.slots || []);
const loadingSlots = ref(false);

const details = reactive({
    name: page.props.auth?.user?.name ?? '',
    phone: page.props.auth?.user?.phone ?? '',
    notes: '',
});

const payment = reactive({
    card: '',
    expiry: '',
    cvc: '',
});

const errors = reactive({});

const selectedService = computed(() => props.services.find((s) => s.id === serviceId.value) || null);

const stepError = computed(() => {
    const v = current.value;
    if (v === 0) return !serviceId.value ? 'Please choose a service to continue.' : '';
    if (v === 1) return !consultationType.value ? 'Please choose how you would like to consult.' : '';
    if (v === 2) return !time.value ? 'Please pick a time slot.' : '';
    if (v === 3) {
        if (!details.name.trim()) return 'Please enter your name.';
        if (!details.phone.trim() || details.phone.trim().length < 7) return 'Please enter a valid phone number.';
        return '';
    }
    if (v === 4) {
        if (!payment.card.replace(/\s/g, '').match(/^\d{16}$/)) return 'Please enter a valid 16-digit card number.';
        if (!payment.expiry.match(/^\d{2}\/\d{2}$/)) return 'Use the format MM/YY.';
        if (!payment.cvc.match(/^\d{3,4}$/)) return 'Please enter a valid CVC.';
        return '';
    }
    return '';
});

const canContinue = computed(() => stepError.value === '');

const next = () => {
    if (!canContinue.value) return;
    if (current.value < steps.length - 1) current.value++;
};

const back = () => {
    if (current.value > 0) current.value--;
};

watch(current, (v) => {
    if (v === 1 && consultationType.value) return;
    if (v === 3 && !details.name) details.name = page.props.auth?.user?.name ?? '';
});

// ---- Date & time ----
const days = computed(() => {
    const list = [];
    for (let i = 0; i < 14; i++) {
        const d = new Date();
        d.setDate(d.getDate() + i);
        list.push({
            iso: d.toISOString().slice(0, 10),
            weekday: d.toLocaleDateString('en-US', { weekday: 'short' }),
            day: d.getDate(),
            month: d.toLocaleDateString('en-US', { month: 'short' }),
        });
    }
    return list;
});

const fetchSlots = async (iso) => {
    loadingSlots.value = true;
    time.value = null;
    try {
        const { data } = await axios.get(route('booking.availability'), { params: { date: iso } });
        slots.value = data.slots;
    } catch {
        slots.value = [];
    } finally {
        loadingSlots.value = false;
    }
};

const pickDate = (day) => {
    if (day.iso === date.value) return;
    date.value = day.iso;
    fetchSlots(day.iso);
};

const selectType = (type) => {
    consultationType.value = type;
    errors.consultationType = '';
};

// ---- Submit ----
const form = useForm({});

const submitting = ref(false);

const confirmPayment = () => {
    if (stepError.value) return;
    submitting.value = true;
    form
        .transform(() => ({
            service_id: serviceId.value,
            consultation_type: consultationType.value,
            date: date.value,
            time: time.value,
            notes: details.notes,
        }))
        .post(route('booking.store'), {
            preserveScroll: true,
            onFinish: () => {
                submitting.value = false;
            },
        });
};

const formatCard = (e) => {
    payment.card = payment.card
        .replace(/\D/g, '')
        .slice(0, 16)
        .replace(/(.{4})/g, '$1 ')
        .trim();
};

const formatExpiry = (e) => {
    let v = payment.expiry.replace(/\D/g, '').slice(0, 4);
    if (v.length > 2) v = v.slice(0, 2) + '/' + v.slice(2);
    payment.expiry = v;
};
</script>

<template>
    <PublicLayout :doctor="doctor">
        <Head title="Book an Appointment" />

        <section class="relative overflow-hidden bg-navy-950 py-14 text-center sm:py-16">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-primary-600/20 blur-3xl" />
                <div class="absolute -right-24 bottom-0 h-72 w-72 rounded-full bg-navy-700/40 blur-3xl" />
            </div>
            <div class="container-px relative">
                <span class="section-eyebrow"><span class="h-1.5 w-1.5 rounded-full bg-primary-500" />Book Appointment</span>
                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Schedule your visit</h1>
                <p class="mx-auto mt-3 max-w-xl text-white/60">
                    Follow the steps below — it takes less than two minutes.
                </p>
            </div>
        </section>

        <section class="section-pad !py-10 sm:!py-12">
            <div class="container-px">
                <div class="mx-auto max-w-5xl">
                    <ProgressSteps :steps="steps" :current="current" />

                    <div class="mt-10 grid gap-8 lg:grid-cols-[1fr_320px]">
                        <!-- Step content -->
                        <div class="min-w-0">
                            <div class="card p-6 sm:p-8">
                                <Transition
                                    :name="'step'"
                                    mode="out-in"
                                    enter-active-class="transition duration-300 ease-out"
                                    enter-from-class="opacity-0 translate-y-2"
                                    enter-to-class="opacity-100 translate-y-0"
                                    leave-active-class="transition duration-150 ease-in"
                                    leave-from-class="opacity-100"
                                    leave-to-class="opacity-0 -translate-y-1"
                                >
                                    <div :key="current" class="min-h-[320px]">
                                        <!-- STEP 0: Service -->
                                        <div v-if="current === 0">
                                            <h2 class="text-lg font-bold text-navy-900">Choose a service</h2>
                                            <p class="mt-1 text-sm text-slate-500">Select the type of consultation you need.</p>
                                            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                                <button
                                                    v-for="service in services"
                                                    :key="service.id"
                                                    type="button"
                                                    @click="serviceId = service.id"
                                                    :class="[
                                                        'flex items-start gap-4 rounded-2xl border p-4 text-left transition-all duration-200',
                                                        serviceId === service.id
                                                            ? 'border-primary-500 bg-primary-50/70 ring-4 ring-primary-500/10'
                                                            : 'border-slate-200 bg-white hover:border-primary-200 hover:bg-slate-50/50',
                                                    ]"
                                                >
                                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-primary-600 shadow-soft ring-1 ring-slate-100">
                                                        <ServiceIcon :name="service.icon" class="h-5 w-5" />
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="font-semibold text-navy-900">{{ service.name }}</p>
                                                        <p class="mt-0.5 text-xs text-slate-500">{{ service.duration_label }} · {{ service.consultation_type_label }}</p>
                                                        <p class="mt-1 text-sm font-bold text-primary-700">{{ service.price_label }}</p>
                                                    </div>
                                                    <span
                                                        :class="[
                                                            'flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-all',
                                                            serviceId === service.id ? 'border-primary-600 bg-primary-600 text-white' : 'border-slate-300',
                                                        ]"
                                                    >
                                                        <CheckIcon v-if="serviceId === service.id" class="h-3 w-3" />
                                                    </span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- STEP 1: Consultation type -->
                                        <div v-else-if="current === 1">
                                            <h2 class="text-lg font-bold text-navy-900">How would you like to consult?</h2>
                                            <p class="mt-1 text-sm text-slate-500">Both options include a personal, confidential consultation.</p>
                                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                                <button
                                                    type="button"
                                                    @click="selectType('online')"
                                                    :class="[
                                                        'rounded-2xl border p-6 text-left transition-all duration-200',
                                                        consultationType === 'online'
                                                            ? 'border-primary-500 bg-primary-50/70 ring-4 ring-primary-500/10'
                                                            : 'border-slate-200 hover:border-primary-200 hover:bg-slate-50/50',
                                                    ]"
                                                >
                                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 ring-1 ring-sky-100">
                                                        <VideoCameraIcon class="h-6 w-6" />
                                                    </div>
                                                    <p class="mt-4 font-bold text-navy-900">Online Consultation</p>
                                                    <p class="mt-1.5 text-sm leading-relaxed text-slate-500">Secure video call from anywhere. E-prescriptions included.</p>
                                                    <ul class="mt-3 space-y-1 text-xs text-slate-500">
                                                        <li>· 15–30 minute call</li>
                                                        <li>· Secure video link sent instantly</li>
                                                    </ul>
                                                </button>
                                                <button
                                                    type="button"
                                                    @click="selectType('physical')"
                                                    :class="[
                                                        'rounded-2xl border p-6 text-left transition-all duration-200',
                                                        consultationType === 'physical'
                                                            ? 'border-primary-500 bg-primary-50/70 ring-4 ring-primary-500/10'
                                                            : 'border-slate-200 hover:border-primary-200 hover:bg-slate-50/50',
                                                    ]"
                                                >
                                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-navy-50 text-navy-600 ring-1 ring-navy-100">
                                                        <BuildingOfficeIcon class="h-6 w-6" />
                                                    </div>
                                                    <p class="mt-4 font-bold text-navy-900">In-Clinic Visit</p>
                                                    <p class="mt-1.5 text-sm leading-relaxed text-slate-500">Meet in person for a full examination and discussion.</p>
                                                    <ul class="mt-3 space-y-1 text-xs text-slate-500">
                                                        <li>· 30–45 minute visit</li>
                                                        <li>· FMH Shadman (Morning) · Mid City Jail Road (Evening)</li>
                                                    </ul>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- STEP 2: Date & time -->
                                        <div v-else-if="current === 2">
                                            <h2 class="text-lg font-bold text-navy-900">Choose date &amp; time</h2>
                                            <p class="mt-1 text-sm text-slate-500">Live availability — select a date, then a time slot.</p>

                                            <div class="mt-6">
                                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Choose date</p>
                                                <div class="scrollbar-thin mt-3 flex gap-2 overflow-x-auto pb-2">
                                                    <button
                                                        v-for="day in days"
                                                        :key="day.iso"
                                                        type="button"
                                                        @click="pickDate(day)"
                                                        :class="[
                                                            'flex w-16 shrink-0 flex-col items-center rounded-xl border-2 py-3 transition-all duration-150',
                                                            date === day.iso
                                                                ? 'border-primary-600 bg-primary-600 text-white shadow-glow'
                                                                : 'border-slate-200 bg-white text-navy-900 hover:border-primary-300',
                                                        ]"
                                                    >
                                                        <span :class="['text-[11px] font-semibold uppercase', date === day.iso ? 'text-primary-100' : 'text-slate-400']">{{ day.weekday }}</span>
                                                        <span class="text-lg font-extrabold">{{ day.day }}</span>
                                                        <span :class="['text-[11px] font-medium', date === day.iso ? 'text-primary-100' : 'text-slate-400']">{{ day.month }}</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="mt-6">
                                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Available times</p>

                                                <div v-if="loadingSlots" class="mt-3 grid grid-cols-3 gap-2 sm:grid-cols-4">
                                                    <div v-for="i in 8" :key="i" class="skeleton h-11 animate-shimmer rounded-xl" />
                                                </div>

                                                <div v-else-if="slots.length" class="mt-3 grid grid-cols-3 gap-2 sm:grid-cols-4">
                                                    <button
                                                        v-for="slot in slots"
                                                        :key="slot.time"
                                                        type="button"
                                                        :disabled="slot.booked"
                                                        @click="time = slot.time"
                                                        :class="[
                                                            'rounded-xl border-2 py-2.5 text-sm font-semibold transition-all duration-150',
                                                            slot.booked
                                                                ? 'cursor-not-allowed border-slate-100 bg-slate-50 text-slate-300 line-through'
                                                                : time === slot.time
                                                                  ? 'border-primary-600 bg-primary-600 text-white shadow-glow'
                                                                  : 'border-slate-200 bg-white text-navy-900 hover:border-primary-400 hover:bg-primary-50',
                                                        ]"
                                                    >
                                                        {{ slot.label }}
                                                    </button>
                                                </div>

                                                <div v-else class="mt-3 rounded-xl border border-dashed border-slate-200 p-6 text-center text-sm text-slate-400">
                                                    No available slots for this date.
                                                </div>
                                            </div>
                                        </div>

                                        <!-- STEP 3: Patient details -->
                                        <div v-else-if="current === 3">
                                            <h2 class="text-lg font-bold text-navy-900">Your details</h2>
                                            <p class="mt-1 text-sm text-slate-500">We'll use these to confirm your appointment.</p>
                                            <div class="mt-6 space-y-5">
                                                <div>
                                                    <Label value="Full name" required />
                                                    <Input v-model="details.name" placeholder="Jane Smith" />
                                                    <FieldError message="Please enter your full name." />
                                                </div>
                                                <div>
                                                    <Label value="Email address" required />
                                                    <Input type="email" :model-value="page.props.auth?.user?.email ?? ''" disabled placeholder="jane@example.com" />
                                                    <p class="mt-1.5 text-xs text-slate-400">We'll send your confirmation and meeting link to this email.</p>
                                                </div>
                                                <div>
                                                    <Label value="Phone number" required />
                                                    <Input v-model="details.phone" type="tel" placeholder="+1 (555) 000-0000" :error="details.phone.trim().length > 0 && details.phone.trim().length < 7" />
                                                    <FieldError message="Please enter a valid phone number." />
                                                </div>
                                                <div>
                                                    <Label value="Reason for visit" />
                                                    <Textarea v-model="details.notes" rows="4" placeholder="Briefly describe what you'd like to discuss (optional)" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- STEP 4: Payment -->
                                        <div v-else-if="current === 4">
                                            <h2 class="text-lg font-bold text-navy-900">Payment</h2>
                                            <p class="mt-1 text-sm text-slate-500">Secure payment via test card — no real charge is made.</p>

                                            <div class="mt-6 space-y-4">
                                                <div>
                                                    <Label value="Card number" required />
                                                    <div class="relative">
                                                        <Input v-model="payment.card" @input="formatCard" placeholder="4242 4242 4242 4242" :error="payment.card.replace(/\s/g,'').length > 0 && !payment.card.replace(/\s/g,'').match(/^\d{16}$/)" />
                                                        <CreditCardIcon class="pointer-events-none absolute inset-y-0 right-3 my-auto h-5 w-5 text-slate-300" />
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <Label value="Expiry (MM/YY)" required />
                                                        <Input v-model="payment.expiry" @input="formatExpiry" placeholder="08/28" :error="payment.expiry.length > 0 && !payment.expiry.match(/^\d{2}\/\d{2}$/)" />
                                                    </div>
                                                    <div>
                                                        <Label value="CVC" required />
                                                        <Input v-model="payment.cvc" type="password" placeholder="123" maxlength="4" :error="payment.cvc.length > 0 && !payment.cvc.match(/^\d{3,4}$/)" />
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-3 text-xs text-slate-500">
                                                    <LockClosedIcon class="h-4 w-4 shrink-0 text-primary-600" />
                                                    Payments are encrypted and processed securely.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </Transition>

                                <!-- Step error -->
                                <p v-if="stepError" class="mt-5 flex items-center gap-1.5 text-sm font-medium text-red-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500" />
                                    {{ stepError }}
                                </p>

                                <!-- Nav buttons -->
                                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
                                    <Button v-if="current > 0" variant="ghost" @click="back">
                                        <ArrowLeftIcon class="h-4 w-4" /> Back
                                    </Button>
                                    <span v-else></span>
                                    <Button v-if="current < steps.length - 1" :disabled="!canContinue" @click="next">
                                        Continue <ArrowRightIcon class="h-4 w-4" />
                                    </Button>
                                    <Button
                                        v-else
                                        :loading="submitting"
                                        :disabled="!canContinue"
                                        variant="success"
                                        @click="confirmPayment"
                                    >
                                        <ShieldCheckIcon class="h-4 w-4" />
                                        Pay {{ selectedService ? 'Rs ' + Number(selectedService.price).toLocaleString() : '' }} &amp; Confirm
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <!-- Summary sidebar -->
                        <aside class="lg:sticky lg:top-8 lg:self-start">
                            <div class="card overflow-hidden">
                                <div class="border-b border-slate-100 bg-slate-50/70 px-5 py-4">
                                    <h3 class="text-sm font-bold uppercase tracking-wider text-navy-900">Appointment Summary</h3>
                                </div>
                                <div class="space-y-4 p-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-primary-50 text-primary-700 ring-1 ring-primary-100">
                                            {{ doctor?.initials }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-navy-900">{{ doctor?.name }}</p>
                                            <p class="text-xs text-slate-400">{{ doctor?.specialization }}</p>
                                        </div>
                                    </div>

                                    <div class="space-y-3 border-t border-slate-100 pt-4 text-sm">
                                        <div class="flex justify-between gap-3">
                                            <span class="text-slate-400">Service</span>
                                            <span class="text-right font-medium text-navy-900">{{ selectedService?.name ?? '—' }}</span>
                                        </div>
                                        <div class="flex justify-between gap-3">
                                            <span class="text-slate-400">Date</span>
                                            <span class="font-medium text-navy-900">
                                                {{ date ? new Date(date + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' }) : '—' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between gap-3">
                                            <span class="text-slate-400">Time</span>
                                            <span class="font-medium text-navy-900">
                                                {{ time ? new Date('2000-01-01T' + time + ':00').toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' }) : '—' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between gap-3">
                                            <span class="text-slate-400">Type</span>
                                            <span class="flex items-center gap-1 font-medium text-navy-900">
                                                <VideoCameraIcon v-if="consultationType === 'online'" class="h-4 w-4 text-sky-500" />
                                                <BuildingOfficeIcon v-else-if="consultationType === 'physical'" class="h-4 w-4 text-navy-500" />
                                                {{ consultationType === 'online' ? 'Online' : consultationType === 'physical' ? 'In Clinic' : '—' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                                        <span class="text-sm text-slate-400">Consultation fee</span>
                                        <span class="text-xl font-extrabold text-navy-900">{{ selectedService ? 'Rs ' + Number(selectedService.price).toLocaleString() : '—' }}</span>
                                    </div>

                                    <p class="flex items-center gap-1.5 rounded-lg bg-primary-50 px-3 py-2 text-[11px] font-medium text-primary-700">
                                        <ClockIcon class="h-3.5 w-3.5" /> Free cancellation up to 24 hours before.
                                    </p>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
