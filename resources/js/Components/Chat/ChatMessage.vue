<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import MessageTicks from '@/Components/Chat/MessageTicks.vue';
import { ArrowUturnLeftIcon, ClipboardIcon, EllipsisVerticalIcon, TrashIcon, XMarkIcon, DocumentArrowDownIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    message: { type: Object, required: true },
    currentUserId: { type: Number, required: true },
});

const emit = defineEmits(['reply', 'deleteMe', 'deleteEveryone']);

const isMine = computed(() => props.message.sender_id === props.currentUserId);
const menuOpen = ref(false);
const menuEl = ref(null);

const localTime = computed(() => {
    const d = new Date(props.message.created_at);
    return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
});

const closeMenu = (e) => {
    if (menuEl.value && !menuEl.value.contains(e.target)) menuOpen.value = false;
};

onMounted(() => document.addEventListener('click', closeMenu));
onBeforeUnmount(() => document.removeEventListener('click', closeMenu));

const formatSize = (bytes) => {
    if (!bytes) return '';
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) return `${Math.round(bytes / 1024)} KB`;
    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
};

const copyMessage = async () => {
    const content = props.message.message || props.message.file_name || '';
    try {
        await navigator.clipboard.writeText(content);
    } catch (e) {
        console.error('Copy failed', e);
    }
    menuOpen.value = false;
};

const scrollToReply = () => {
    const el = document.getElementById(`msg-${props.message.reply_to.id}`);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        el.classList.add('flash-highlight');
        setTimeout(() => el.classList.remove('flash-highlight'), 1600);
    }
};

const deletedText = 'This message was deleted';
</script>

<template>
    <div :id="`msg-${message.id}`" class="flex scroll-mt-10" :class="isMine ? 'justify-end' : 'justify-start'">
        <div class="group relative max-w-[80%] sm:max-w-[70%]">
            <!-- Action menu -->
            <div v-if="menuOpen" ref="menuEl" class="absolute top-0 z-20 w-52 rounded-2xl border border-slate-200/80 bg-white p-1.5 shadow-xl" :class="isMine ? 'right-2' : 'left-2'">
                <button type="button" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-50" @click="emit('reply', message)">
                    <ArrowUturnLeftIcon class="h-4 w-4 text-slate-400" />
                    Reply
                </button>
                <button v-if="message.message || message.file_name" type="button" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-50" @click="copyMessage">
                    <ClipboardIcon class="h-4 w-4 text-slate-400" />
                    Copy
                </button>
                <button type="button" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-left text-sm font-medium text-slate-700 transition hover:bg-slate-50" @click="emit('deleteMe', message)">
                    <TrashIcon class="h-4 w-4 text-slate-400" />
                    Delete for me
                </button>
                <button v-if="isMine" type="button" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50" @click="emit('deleteEveryone', message)">
                    <XMarkIcon class="h-4 w-4" />
                    Delete for everyone
                </button>
            </div>

            <button
                type="button"
                class="absolute top-1/2 z-10 -translate-y-1/2 rounded-full p-1.5 text-slate-400 opacity-0 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:text-slate-600 group-hover:opacity-100"
                :class="isMine ? '-left-11' : '-right-11'"
                aria-label="Message options"
                @click.stop="menuOpen = !menuOpen"
            >
                <EllipsisVerticalIcon class="h-4 w-4" />
            </button>

            <div
                class="rounded-2xl px-4 py-2.5 text-sm leading-relaxed shadow-sm"
                :class="
                    message.deleted
                        ? 'rounded-bl-md bg-slate-100 italic text-slate-400 ring-1 ring-slate-200/60'
                        : isMine
                          ? 'rounded-br-md bg-primary-600 text-white'
                          : 'rounded-bl-md bg-white text-navy-900 ring-1 ring-slate-200/80'
                "
            >
                <!-- Deleted placeholder -->
                <template v-if="message.deleted">
                    <p class="whitespace-pre-wrap break-words">{{ deletedText }}</p>
                </template>

                <template v-else>
                    <!-- Reply preview -->
                    <button v-if="message.reply_to" type="button" class="mb-2 block w-full rounded-lg border-l-4 px-3 py-1.5 text-left transition" :class="isMine ? 'border-white/60 bg-white/15' : 'border-primary-300 bg-primary-50'" @click="scrollToReply">
                        <p class="truncate text-xs font-bold" :class="isMine ? 'text-white' : 'text-primary-700'">{{ message.reply_to.sender_name }}</p>
                        <p class="truncate text-xs" :class="isMine ? 'text-white/80' : 'text-slate-500'">{{ message.reply_to.message }}</p>
                    </button>

                    <!-- Image -->
                    <a v-if="message.type === 'image' && message.file_url" :href="message.file_url" target="_blank" class="mb-1.5 block overflow-hidden rounded-xl" :class="isMine ? 'bg-white/10' : 'bg-slate-100'">
                        <img :src="message.file_url" :alt="message.file_name || 'Attachment'" class="max-h-72 w-full object-cover" />
                    </a>

                    <!-- File -->
                    <a v-else-if="message.type === 'file' && message.file_url" :href="message.file_url" target="_blank" class="mb-1.5 flex items-center gap-3 rounded-xl p-2.5" :class="isMine ? 'bg-white/15' : 'bg-slate-50 ring-1 ring-slate-200/70'">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg" :class="isMine ? 'bg-white/20 text-white' : 'bg-primary-50 text-primary-600'">
                            <DocumentArrowDownIcon class="h-5 w-5" />
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-xs font-semibold" :class="isMine ? 'text-white' : 'text-navy-900'">{{ message.file_name }}</span>
                            <span class="block text-[11px]" :class="isMine ? 'text-white/70' : 'text-slate-400'">{{ formatSize(message.file_size) }}</span>
                        </span>
                    </a>

                    <p v-if="message.message" class="whitespace-pre-wrap break-words">{{ message.message }}</p>
                </template>
            </div>

            <p class="mt-1 flex items-center gap-1 px-1 text-[10px] font-medium text-slate-400" :class="isMine ? 'justify-end' : ''">
                <span>{{ localTime }}</span>
                <MessageTicks v-if="isMine && !message.deleted" :delivered="Boolean(message.is_delivered)" :read="Boolean(message.is_read)" />
            </p>
        </div>
    </div>
</template>

<style scoped>
.flash-highlight {
    animation: flash 1.6s ease-out;
}

@keyframes flash {
    0%,
    40% {
        background-color: rgb(2 132 199 / 0.25);
    }
    100% {
        background-color: transparent;
    }
}
</style>
