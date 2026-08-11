<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import ChatMessage from '@/Components/Chat/ChatMessage.vue';
import MessageComposer from '@/Components/Chat/MessageComposer.vue';
import {
    HomeIcon,
    PlusIcon,
    ChatBubbleLeftRightIcon,
    UserCircleIcon,
    InboxIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    conversation: { type: Object, required: true },
    messages: { type: Array, default: () => [] },
    counterpart: { type: Object, default: () => ({}) },
});

const page = usePage();
const currentUserId = page.props.auth?.user?.id;

const navItems = [
    { label: 'Dashboard', route: 'dashboard', icon: HomeIcon },
    { label: 'Book Appointment', route: 'booking.create', icon: PlusIcon },
    { label: 'Messages', route: 'chat.index', icon: ChatBubbleLeftRightIcon },
    { label: 'My Profile', route: 'profile.edit', icon: UserCircleIcon },
];

const messages = ref([...props.messages]);
const sending = ref(false);
const replyTo = ref(null);

const peerOnline = ref(false);
const counterpartLastSeen = ref(props.counterpart?.last_seen_at || null);

const listEl = ref(null);

const scrollToBottom = async () => {
    await nextTick();
    if (listEl.value) listEl.value.scrollTop = listEl.value.scrollHeight;
};

const timeLabel = (m) => new Date(m.created_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });

const dayLabel = (createdAt) => new Date(createdAt).toLocaleDateString('en-US', { weekday: 'short', day: 'numeric', month: 'short' });

const isSameDay = (a, b) => {
    const d1 = new Date(a);
    const d2 = new Date(b);
    return d1.toDateString() === d2.toDateString();
};

const pushMessage = (message) => {
    if (messages.value.some((m) => m.id === message.id)) return;
    const index = messages.value.findIndex((m) => new Date(m.created_at) > new Date(message.created_at));
    if (index === -1) {
        messages.value.push(message);
    } else {
        messages.value.splice(index, 0, message);
    }
    scrollToBottom();
};

const markRead = async () => {
    try {
        await axios.post(route('chat.read', props.conversation.id));
    } catch (e) {
        console.error('Failed to mark as read', e);
    }
};

const send = async (text) => {
    sending.value = true;
    try {
        const { data } = await axios.post(route('chat.send', props.conversation.id), {
            message: text,
            reply_to_id: replyTo.value?.id || null,
        });
        pushMessage(data.message);
        replyTo.value = null;
    } catch (e) {
        console.error('Failed to send message', e);
    } finally {
        sending.value = false;
    }
};

const attach = async (file) => {
    sending.value = true;
    try {
        const form = new FormData();
        form.append('file', file);
        const { data } = await axios.post(route('chat.attach', props.conversation.id), form, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        pushMessage(data.message);
    } catch (e) {
        console.error('Failed to attach file', e);
    } finally {
        sending.value = false;
    }
};

const setReplyTo = (message) => {
    replyTo.value = message;
};

const deleteMe = async (message) => {
    try {
        await axios.post(route('chat.delete-me', [props.conversation.id, message.id]));
        const idx = messages.value.findIndex((m) => m.id === message.id);
        if (idx !== -1) messages.value.splice(idx, 1);
    } catch (e) {
        console.error('Failed to delete message', e);
    }
};

const deleteEveryone = async (message) => {
    try {
        await axios.post(route('chat.delete-everyone', [props.conversation.id, message.id]));
        applyDeleted(message.id);
    } catch (e) {
        console.error('Failed to delete message for everyone', e);
    }
};

const applyDeleted = (messageId) => {
    const message = messages.value.find((m) => m.id === messageId);
    if (!message) return;
    message.deleted = true;
    message.message = '';
    message.file_url = null;
    message.file_name = null;
};

const fetchPeerStatus = async () => {
    try {
        const { data } = await axios.get(route('chat.peer-status', props.conversation.id));
        counterpartLastSeen.value = data.last_seen_at || null;
    } catch (e) {
        console.error('Failed to fetch peer status', e);
    }
};

const formatLastSeen = (iso) => {
    if (!iso) return 'Last seen recently';
    const d = new Date(iso);
    const now = new Date();
    const diffMin = Math.floor((now - d) / 60000);
    if (diffMin < 1) return 'Last seen just now';
    if (diffMin < 60) return `Last seen ${diffMin} min ago`;
    if (d.toDateString() === now.toDateString()) return `Last seen today at ${d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })}`;
    return `Last seen ${d.toLocaleDateString('en-US', { day: 'numeric', month: 'short' })}`;
};

const statusText = computed(() => (peerOnline.value ? 'Online' : formatLastSeen(counterpartLastSeen.value)));

let channel = null;
let presence = null;
let heartbeat = null;

onMounted(() => {
    scrollToBottom();

    if (!window.Echo) return;

    channel = window.Echo.private(`chat.${props.conversation.id}`);
    channel.listen('.message.sent', (e) => {
        if (e.message?.sender_id !== currentUserId) markRead();
        pushMessage(e.message);
    });
    channel.listen('.message.status', (e) => {
        const message = messages.value.find((m) => m.id === e.message_id);
        if (!message) return;
        message.is_delivered = Boolean(e.is_delivered);
        message.is_read = Boolean(e.is_read);
    });
    channel.listen('.message.deleted', (e) => {
        applyDeleted(e.message_id);
    });

    presence = window.Echo.join('online');
    presence.here((members) => {
        peerOnline.value = members.some((m) => m.id === props.counterpart?.id);
    });
    presence.joining((member) => {
        if (member.id === props.counterpart?.id) peerOnline.value = true;
    });
    presence.leaving((member) => {
        if (member.id === props.counterpart?.id) {
            peerOnline.value = false;
            fetchPeerStatus();
        }
    });

    heartbeat = setInterval(() => {
        axios.post(route('presence.heartbeat')).catch(() => {});
    }, 25000);
    axios.post(route('presence.heartbeat')).catch(() => {});
});

onBeforeUnmount(() => {
    if (heartbeat) clearInterval(heartbeat);
    if (presence) window.Echo.leaveChannel('presence-online');
    if (channel) {
        channel.stopListening('.message.sent');
        channel.stopListening('.message.status');
        channel.stopListening('.message.deleted');
        window.Echo.leaveChannel(`private-chat.${props.conversation.id}`);
    }
});
</script>

<template>
    <DashboardLayout :nav-items="navItems" title="Messages">
        <Head title="Messages" />

        <div class="mx-auto flex h-[calc(100vh-8rem)] max-w-3xl flex-col overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-card">
            <!-- Header -->
            <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
                <div class="relative">
                    <Avatar :name="counterpart?.name" tone="primary" />
                    <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full ring-2 ring-white" :class="peerOnline ? 'bg-emerald-500' : 'bg-slate-300'" />
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="truncate text-sm font-bold text-navy-950">{{ counterpart?.name }}</h2>
                    <p class="truncate text-xs" :class="peerOnline ? 'font-medium text-emerald-600' : 'text-slate-400'">{{ statusText }}</p>
                </div>
                <span class="hidden rounded-full bg-primary-50 px-3 py-1 text-[11px] font-semibold text-primary-700 ring-1 ring-primary-100 sm:inline-flex">
                    Direct chat
                </span>
            </div>

            <!-- Messages -->
            <div ref="listEl" class="flex-1 space-y-1 overflow-y-auto bg-slate-50/70 px-5 py-5 scrollbar-thin">
                <div v-if="!messages.length" class="flex h-full flex-col items-center justify-center text-center">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                        <InboxIcon class="h-7 w-7" />
                    </div>
                    <h3 class="mt-4 text-sm font-semibold text-navy-900">Start the conversation</h3>
                    <p class="mt-1 max-w-xs text-sm text-slate-500">Ask about appointments, surgery details, or anything else — we'll reply soon.</p>
                </div>

                <template v-for="(msg, i) in messages" :key="msg.id">
                    <div v-if="i === 0 || !isSameDay(messages[i - 1].created_at, msg.created_at)" class="py-2 text-center">
                        <span class="rounded-full bg-white px-3 py-1 text-[11px] font-semibold text-slate-400 ring-1 ring-slate-200">{{ dayLabel(msg.created_at) }}</span>
                    </div>

                    <ChatMessage :message="msg" :current-user-id="currentUserId" @reply="setReplyTo" @delete-me="deleteMe" @delete-everyone="deleteEveryone" />
                </template>
            </div>

            <!-- Composer -->
            <MessageComposer
                placeholder="Type a message…"
                :sending="sending"
                :reply-to="replyTo"
                @send="send"
                @attach="attach"
                @cancel-reply="replyTo = null"
            />
        </div>
    </DashboardLayout>
</template>
