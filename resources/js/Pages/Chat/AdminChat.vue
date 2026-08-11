<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Avatar from '@/Components/ui/Avatar.vue';
import ChatMessage from '@/Components/Chat/ChatMessage.vue';
import MessageComposer from '@/Components/Chat/MessageComposer.vue';
import {
    HomeIcon,
    UserCircleIcon,
    ChatBubbleLeftRightIcon,
    InboxIcon,
    MagnifyingGlassIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    conversations: { type: Array, default: () => [] },
});

const page = usePage();
const currentUserId = page.props.auth?.user?.id;

const navItems = [
    { label: 'Dashboard', route: 'admin.dashboard', icon: HomeIcon },
    { label: 'Messages', route: 'admin.chat', icon: ChatBubbleLeftRightIcon },
    { label: 'My Profile', route: 'profile.edit', icon: UserCircleIcon },
];

const conversations = ref([...props.conversations]);
const activeId = ref(null);
const messages = ref([]);
const sending = ref(false);
const loadingMessages = ref(false);
const query = ref('');
const replyTo = ref(null);
const onlineUsers = ref([]);

const activeConversation = computed(() => conversations.value.find((c) => c.id === activeId.value) ?? null);

const filteredConversations = computed(() => {
    const q = query.value.trim().toLowerCase();
    if (!q) return conversations.value;
    return conversations.value.filter((c) => (c.patient?.name ?? '').toLowerCase().includes(q));
});

const totalUnread = computed(() => conversations.value.reduce((sum, c) => sum + (c.unread_count || 0), 0));

const isOnline = (patientId) => onlineUsers.value.includes(patientId);

const activePatientOnline = computed(() => (activeConversation.value?.patient?.id != null ? isOnline(activeConversation.value.patient.id) : false));

const listEl = ref(null);

const scrollToBottom = async () => {
    await nextTick();
    if (listEl.value) listEl.value.scrollTop = listEl.value.scrollHeight;
};

const dayLabel = (createdAt) => new Date(createdAt).toLocaleDateString('en-US', { weekday: 'short', day: 'numeric', month: 'short' });

const isSameDay = (a, b) => {
    const d1 = new Date(a);
    const d2 = new Date(b);
    return d1.toDateString() === d2.toDateString();
};

const relativeTime = (iso) => {
    if (!iso) return '';
    const diff = Date.now() - new Date(iso).getTime();
    const mins = Math.floor(diff / 60000);
    if (mins < 1) return 'now';
    if (mins < 60) return `${mins}m ago`;
    const hrs = Math.floor(mins / 60);
    if (hrs < 24) return `${hrs}h ago`;
    const days = Math.floor(hrs / 24);
    if (days < 7) return `${days}d ago`;
    return new Date(iso).toLocaleDateString('en-US', { day: 'numeric', month: 'short' });
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

const formatTimeLabel = (createdAt) => {
    if (!createdAt) return '';
    return new Date(createdAt).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
};

const updateSidebarPreview = (conversation, message) => {
    conversation.last_message = {
        message: message.deleted ? 'This message was deleted' : (message.message || (message.type === 'image' ? '📷 Photo' : '📎 ' + (message.file_name || 'File'))),
        sender_id: message.sender_id,
        created_at: message.created_at,
    };
};

const markRead = async (conversation) => {
    conversation.unread_count = 0;
    try {
        await axios.post(route('chat.read', conversation.id));
    } catch (e) {
        console.error('Failed to mark as read', e);
    }
};

const markDelivered = async (conversation) => {
    try {
        await axios.post(route('chat.delivered', conversation.id));
    } catch (e) {
        console.error('Failed to mark as delivered', e);
    }
};

const openConversation = async (conversation) => {
    if (activeId.value === conversation.id) return;
    activeId.value = conversation.id;
    messages.value = [];
    replyTo.value = null;
    loadingMessages.value = true;
    try {
        const { data } = await axios.get(route('chat.messages', conversation.id));
        messages.value = data.messages;
        markRead(conversation);
        scrollToBottom();
    } catch (e) {
        console.error('Failed to load messages', e);
    } finally {
        loadingMessages.value = false;
    }
};

const send = async (text) => {
    if (!activeConversation.value) return;
    sending.value = true;
    try {
        const { data } = await axios.post(route('chat.send', activeConversation.value.id), {
            message: text,
            reply_to_id: replyTo.value?.id || null,
        });
        pushMessage(data.message);
        updateSidebarPreview(activeConversation.value, data.message);
        replyTo.value = null;
    } catch (e) {
        console.error('Failed to send message', e);
    } finally {
        sending.value = false;
    }
};

const attach = async (file) => {
    if (!activeConversation.value) return;
    sending.value = true;
    try {
        const form = new FormData();
        form.append('file', file);
        const { data } = await axios.post(route('chat.attach', activeConversation.value.id), form, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        pushMessage(data.message);
        updateSidebarPreview(activeConversation.value, data.message);
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
    if (!activeConversation.value) return;
    try {
        await axios.post(route('chat.delete-me', [activeConversation.value.id, message.id]));
        const idx = messages.value.findIndex((m) => m.id === message.id);
        if (idx !== -1) messages.value.splice(idx, 1);
    } catch (e) {
        console.error('Failed to delete message', e);
    }
};

const deleteEveryone = async (message) => {
    if (!activeConversation.value) return;
    try {
        await axios.post(route('chat.delete-everyone', [activeConversation.value.id, message.id]));
        applyDeleted(message.id);
    } catch (e) {
        console.error('Failed to delete message for everyone', e);
    }
};

const applyDeleted = (messageId) => {
    const message = messages.value.find((m) => m.id === messageId);
    if (message) {
        message.deleted = true;
        message.message = '';
        message.file_url = null;
        message.file_name = null;
    }
    const conversation = conversations.value.find((c) => c.last_message?.message && c.id === (message?.conversation_id ?? activeConversation.value?.id));
    if (conversation) {
        conversation.last_message.message = 'This message was deleted';
    }
};

const fetchPeerStatus = async (conversation) => {
    try {
        const { data } = await axios.get(route('chat.peer-status', conversation.id));
        if (conversation.patient) conversation.patient.last_seen_at = data.last_seen_at || null;
    } catch (e) {
        console.error('Failed to fetch peer status', e);
    }
};

let channels = [];
let presence = null;
let heartbeat = null;

const handleIncoming = (e) => {
    const message = e.message;
    const conversation = conversations.value.find((c) => c.id === message.conversation_id);
    if (!conversation) return;

    updateSidebarPreview(conversation, message);

    if (activeId.value === message.conversation_id) {
        pushMessage(message);
        if (message.sender_id !== currentUserId) markRead(conversation);
    } else {
        if (message.sender_id !== currentUserId) {
            conversation.unread_count = (conversation.unread_count || 0) + 1;
            markDelivered(conversation);
        }
        const idx = conversations.value.indexOf(conversation);
        if (idx > 0) {
            conversations.value.splice(idx, 1);
            conversations.value.unshift(conversation);
        }
    }
};

const handleStatus = (e) => {
    const message = messages.value.find((m) => m.id === e.message_id);
    if (!message) return;
    message.is_delivered = Boolean(e.is_delivered);
    message.is_read = Boolean(e.is_read);
};

const handleDeleted = (e) => {
    applyDeleted(e.message_id);
};

const subscribe = () => {
    if (!window.Echo) return;
    channels.forEach((ch) => {
        ch.stopListening('.message.sent');
        ch.stopListening('.message.status');
        ch.stopListening('.message.deleted');
        window.Echo.leaveChannel(`private-chat.${ch.name.replace('private-', '')}`);
    });
    channels = [];

    conversations.value.forEach((conversation) => {
        const channel = window.Echo.private(`chat.${conversation.id}`);
        channel.listen('.message.sent', handleIncoming);
        channel.listen('.message.status', handleStatus);
        channel.listen('.message.deleted', handleDeleted);
        channels.push(channel);
    });
};

const subscribePresence = () => {
    if (!window.Echo) return;
    presence = window.Echo.join('online');
    presence.here((members) => {
        onlineUsers.value = members.map((m) => m.id);
    });
    presence.joining((member) => {
        if (!onlineUsers.value.includes(member.id)) onlineUsers.value.push(member.id);
    });
    presence.leaving((member) => {
        onlineUsers.value = onlineUsers.value.filter((id) => id !== member.id);
        const conversation = conversations.value.find((c) => c.patient?.id === member.id);
        if (conversation) fetchPeerStatus(conversation);
    });
};

onMounted(() => {
    if (conversations.value.length) {
        openConversation(conversations.value[0]);
    }
    subscribe();
    subscribePresence();
    conversations.value
        .filter((c) => (c.unread_count || 0) > 0)
        .forEach(markDelivered);

    heartbeat = setInterval(() => {
        axios.post(route('presence.heartbeat')).catch(() => {});
    }, 25000);
    axios.post(route('presence.heartbeat')).catch(() => {});
});

onBeforeUnmount(() => {
    if (heartbeat) clearInterval(heartbeat);
    if (presence) window.Echo.leaveChannel('presence-online');
    channels.forEach((ch) => {
        ch.stopListening('.message.sent');
        ch.stopListening('.message.status');
        ch.stopListening('.message.deleted');
        window.Echo.leaveChannel(`private-chat.${ch.name.replace('private-', '')}`);
    });
    channels = [];
});
</script>

<template>
    <DashboardLayout :nav-items="navItems" title="Messages">
        <Head title="Messages" />

        <div class="mx-auto h-[calc(100vh-8rem)] max-w-6xl overflow-hidden rounded-3xl border border-slate-200/80 bg-white shadow-card">
            <div class="flex h-full">
                <!-- Conversation list -->
                <div class="hidden w-80 shrink-0 flex-col border-r border-slate-100 sm:flex">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-sm font-bold text-navy-950">Conversations</h2>
                            <span v-if="totalUnread" class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-600 text-xs font-bold text-white">{{ totalUnread }}</span>
                        </div>
                        <div class="relative mt-3">
                            <MagnifyingGlassIcon class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                            <input
                                v-model="query"
                                type="text"
                                placeholder="Search patients…"
                                class="w-full rounded-xl border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-navy-900 placeholder-slate-400 focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-500/10"
                            />
                        </div>
                    </div>

                    <div class="flex-1 space-y-1 overflow-y-auto p-2 scrollbar-thin">
                        <div v-if="!filteredConversations.length" class="flex flex-col items-center px-4 py-12 text-center">
                            <InboxIcon class="h-8 w-8 text-slate-300" />
                            <p class="mt-3 text-sm font-semibold text-navy-900">No conversations yet</p>
                            <p class="mt-1 text-xs text-slate-400">When patients message you, they'll appear here.</p>
                        </div>

                        <button
                            v-for="conversation in filteredConversations"
                            :key="conversation.id"
                            type="button"
                            @click="openConversation(conversation)"
                            class="flex w-full items-center gap-3 rounded-2xl px-3 py-3 text-left transition"
                            :class="activeId === conversation.id ? 'bg-primary-50 ring-1 ring-primary-100' : 'hover:bg-slate-50'"
                        >
                            <div class="relative shrink-0">
                                <Avatar :name="conversation.patient?.name" tone="sky" size="md" />
                                <span
                                    class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full ring-2 ring-white"
                                    :class="isOnline(conversation.patient?.id) ? 'bg-emerald-500' : 'bg-slate-300'"
                                />
                                <span v-if="conversation.unread_count" class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-500 px-1 text-[10px] font-bold text-white ring-2 ring-white">
                                    {{ conversation.unread_count }}
                                </span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-baseline justify-between gap-2">
                                    <p class="truncate text-sm font-semibold" :class="conversation.unread_count ? 'text-navy-950' : 'text-navy-900'">
                                        {{ conversation.patient?.name }}
                                    </p>
                                    <span v-if="conversation.last_message" class="shrink-0 text-[10px] font-medium text-slate-400">{{ formatTimeLabel(conversation.last_message.created_at) }}</span>
                                </div>
                                <p class="mt-0.5 truncate text-xs" :class="conversation.unread_count ? 'font-semibold text-navy-900' : 'text-slate-400'">
                                    {{ conversation.last_message?.message || 'No messages yet' }}
                                </p>
                                <p class="mt-0.5 truncate text-[10px]" :class="isOnline(conversation.patient?.id) ? 'font-medium text-emerald-600' : 'text-slate-400'">
                                    {{ isOnline(conversation.patient?.id) ? 'Online' : formatLastSeen(conversation.patient?.last_seen_at) }}
                                </p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Chat panel -->
                <div class="flex min-w-0 flex-1 flex-col">
                    <!-- Header -->
                    <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-4">
                        <template v-if="activeConversation">
                            <div class="relative">
                                <Avatar :name="activeConversation.patient?.name" tone="sky" />
                                <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full ring-2 ring-white" :class="activePatientOnline ? 'bg-emerald-500' : 'bg-slate-300'" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 class="truncate text-sm font-bold text-navy-950">{{ activeConversation.patient?.name }}</h2>
                                <p class="truncate text-xs" :class="activePatientOnline ? 'font-medium text-emerald-600' : 'text-slate-400'">
                                    {{ activePatientOnline ? 'Online' : formatLastSeen(activeConversation.patient?.last_seen_at) }}
                                </p>
                            </div>
                        </template>
                        <template v-else>
                            <div class="min-w-0 flex-1">
                                <h2 class="text-sm font-bold text-navy-950">Messages</h2>
                                <p class="text-xs text-slate-400">Select a conversation to start chatting.</p>
                            </div>
                        </template>
                    </div>

                    <!-- Messages -->
                    <div ref="listEl" class="flex-1 space-y-1 overflow-y-auto bg-slate-50/70 px-5 py-5 scrollbar-thin">
                        <div v-if="loadingMessages" class="flex h-full items-center justify-center">
                            <div class="h-8 w-8 animate-spin rounded-full border-2 border-primary-600 border-t-transparent" />
                        </div>

                        <template v-else-if="activeConversation">
                            <div v-if="!messages.length" class="flex h-full flex-col items-center justify-center text-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary-50 text-primary-600 ring-1 ring-primary-100">
                                    <InboxIcon class="h-7 w-7" />
                                </div>
                                <h3 class="mt-4 text-sm font-semibold text-navy-900">No messages yet</h3>
                                <p class="mt-1 max-w-xs text-sm text-slate-500">Say hello to {{ activeConversation.patient?.name }} to get the conversation started.</p>
                            </div>

                            <template v-for="(msg, i) in messages" :key="msg.id">
                                <div v-if="i === 0 || !isSameDay(messages[i - 1].created_at, msg.created_at)" class="py-2 text-center">
                                    <span class="rounded-full bg-white px-3 py-1 text-[11px] font-semibold text-slate-400 ring-1 ring-slate-200">{{ dayLabel(msg.created_at) }}</span>
                                </div>

                                <ChatMessage :message="msg" :current-user-id="currentUserId" @reply="setReplyTo" @delete-me="deleteMe" @delete-everyone="deleteEveryone" />
                            </template>
                        </template>
                    </div>

                    <!-- Composer -->
                    <MessageComposer
                        v-if="activeConversation"
                        placeholder="Type a reply…"
                        :sending="sending"
                        :reply-to="replyTo"
                        @send="send"
                        @attach="attach"
                        @cancel-reply="replyTo = null"
                    />
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
