<script setup>
import { reactive, ref } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Button from '@/Components/ui/Button.vue';
import Input from '@/Components/ui/Input.vue';
import Label from '@/Components/ui/Label.vue';
import Textarea from '@/Components/ui/Textarea.vue';
import FieldError from '@/Components/ui/FieldError.vue';
import { toast } from '@/lib/toast';
import { MapPinIcon, PhoneIcon, EnvelopeIcon } from '@heroicons/vue/24/outline';

defineProps({
    doctor: { type: Object, default: null },
});

const form = reactive({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const errors = reactive({});
const sending = ref(false);

const validate = () => {
    Object.keys(errors).forEach((k) => delete errors[k]);
    if (!form.name.trim()) errors.name = 'Please enter your name.';
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) errors.email = 'Please enter a valid email address.';
    if (!form.subject.trim()) errors.subject = 'Please add a short subject.';
    if (form.message.trim().length < 10) errors.message = 'Please write a message of at least 10 characters.';
    return Object.keys(errors).length === 0;
};

const submit = () => {
    if (!validate()) return;
    sending.value = true;
    setTimeout(() => {
        sending.value = false;
        toast('Your message has been sent. We will reply within one business day.', 'success', 'Message sent');
        Object.assign(form, { name: '', email: '', subject: '', message: '' });
    }, 900);
};

const contactCards = [
    { icon: MapPinIcon, title: 'FMH · Shadman, Lahore', subtitle: 'Morning clinic', lines: ['Fatima Memorial Hospital', 'Shadman, Lahore'] },
    { icon: MapPinIcon, title: 'Mid City Hospital · Jail Road', subtitle: 'Evening clinic', lines: ['Jail Road, Lahore'] },
    { icon: PhoneIcon, title: 'Call us', lines: ['+92 300 1234567', 'For appointments & queries'] },
    { icon: EnvelopeIcon, title: 'Email us', lines: ['care@medicare.test', 'Replies within 24 hours'] },
];
</script>

<template>
    <PublicLayout :doctor="doctor">
        <Head title="Contact" />

        <section class="relative overflow-hidden bg-navy-950 py-20 text-center">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-24 top-0 h-80 w-80 rounded-full bg-primary-600/20 blur-3xl" />
                <div class="absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-navy-700/40 blur-3xl" />
            </div>
            <div class="container-px relative">
                <span class="section-eyebrow"><span class="h-1.5 w-1.5 rounded-full bg-primary-500" />Get in touch</span>
                <h1 class="mx-auto max-w-3xl text-balance text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                    We're here to help
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-lg leading-relaxed text-white/70">
                    Questions about a service, an appointment or your health? Reach out and our team will get back to you.
                </p>
            </div>
        </section>

        <section class="section-pad">
            <div class="container-px grid gap-10 lg:grid-cols-[0.9fr_1.1fr]">
                <!-- Info -->
                <div class="space-y-4">
                    <div v-for="card in contactCards" :key="card.title" class="card flex items-start gap-4 p-5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                            <component :is="card.icon" class="h-5 w-5" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-navy-900">{{ card.title }}</h3>
                            <p v-if="card.subtitle" class="mt-0.5 text-xs font-semibold text-primary-600">{{ card.subtitle }}</p>
                            <p v-for="line in card.lines" :key="line" class="mt-0.5 text-sm text-slate-500">{{ line }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-primary-100 bg-primary-50/60 p-5">
                        <h3 class="text-sm font-bold text-primary-800">Emergency?</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-primary-900/80">
                            If you are experiencing a medical emergency, please call <span class="font-bold">1122</span> or visit your nearest emergency department immediately.
                        </p>
                    </div>
                </div>

                <!-- Form -->
                <div class="card p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-navy-900">Send us a message</h2>
                    <p class="mt-1.5 text-sm text-slate-500">We usually reply within one business day.</p>

                    <form class="mt-6 space-y-5" @submit.prevent="submit">
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <Label for="c-name" value="Full name" required />
                                <Input id="c-name" v-model="form.name" placeholder="Jane Smith" :error="!!errors.name" autocomplete="name" />
                                <FieldError :message="errors.name" />
                            </div>
                            <div>
                                <Label for="c-email" value="Email address" required />
                                <Input id="c-email" type="email" v-model="form.email" placeholder="jane@example.com" :error="!!errors.email" autocomplete="email" />
                                <FieldError :message="errors.email" />
                            </div>
                        </div>

                        <div>
                            <Label for="c-subject" value="Subject" required />
                            <Input id="c-subject" v-model="form.subject" placeholder="What is your message about?" :error="!!errors.subject" />
                            <FieldError :message="errors.subject" />
                        </div>

                        <div>
                            <Label for="c-message" value="Message" required />
                            <Textarea id="c-message" v-model="form.message" rows="5" placeholder="Tell us how we can help..." :error="!!errors.message" />
                            <FieldError :message="errors.message" />
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <p class="text-xs text-slate-400">We'll never share your details.</p>
                            <Button type="submit" :loading="sending">Send message</Button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
