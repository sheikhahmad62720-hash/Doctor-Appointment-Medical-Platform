<script setup>
import { ref } from 'vue';
import Button from '@/Components/ui/Button.vue';
import EmojiPicker from '@/Components/Chat/EmojiPicker.vue';
import { FaceSmileIcon, PaperClipIcon, PaperAirplaneIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    placeholder: { type: String, default: 'Type a message…' },
    sending: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    replyTo: { type: Object, default: null },
});

const emit = defineEmits(['send', 'attach', 'cancelReply']);

const text = ref('');
const showEmoji = ref(false);
const fileInput = ref(null);

const submit = () => {
    const trimmed = text.value.trim();
    if (!trimmed || props.sending || props.disabled) return;
    emit('send', trimmed);
    text.value = '';
    showEmoji.value = false;
};

const pickEmoji = (emoji) => {
    text.value += emoji;
    text.value = text.value.replace(/\s+/g, ' ').trimStart();
};

const onFileChange = (e) => {
    const file = e.target.files?.[0];
    if (file) emit('attach', file);
    e.target.value = '';
};

const replyLabel = props.replyTo ? props.replyTo.sender_name || 'Message' : 'Message';
const replySnippet = props.replyTo
    ? props.replyTo.type === 'image'
        ? '📷 Photo'
        : props.replyTo.type === 'file'
          ? '📎 ' + (props.replyTo.file_name || 'File')
          : props.replyTo.message || ''
    : '';
</script>

<template>
    <div class="border-t border-slate-100 bg-white px-4 py-3">
        <!-- Reply preview -->
        <div v-if="replyTo" class="mb-2 flex items-center gap-3 rounded-xl border-l-4 border-primary-500 bg-primary-50/70 px-3 py-2">
            <div class="min-w-0 flex-1">
                <p class="truncate text-xs font-bold text-primary-700">Replying to {{ replyLabel }}</p>
                <p class="truncate text-xs text-slate-500">{{ replySnippet }}</p>
            </div>
            <button type="button" class="shrink-0 rounded-lg p-1 text-slate-400 transition hover:bg-white hover:text-slate-600" @click="emit('cancelReply')" aria-label="Cancel reply">
                <XMarkIcon class="h-4 w-4" />
            </button>
        </div>

        <div class="relative">
            <EmojiPicker v-if="showEmoji" @select="pickEmoji" @close="showEmoji = false" />

            <form class="flex items-end gap-2" @submit.prevent="submit">
                <button
                    type="button"
                    class="shrink-0 rounded-xl p-2.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                    :class="showEmoji ? 'bg-primary-50 text-primary-600' : ''"
                    aria-label="Emoji"
                    @click="showEmoji = !showEmoji"
                >
                    <FaceSmileIcon class="h-5 w-5" />
                </button>

                <button type="button" class="shrink-0 rounded-xl p-2.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" aria-label="Attach file" @click="fileInput?.click()">
                    <PaperClipIcon class="h-5 w-5" />
                </button>
                <input ref="fileInput" type="file" class="hidden" @change="onFileChange" />

                <textarea
                    v-model="text"
                    rows="1"
                    :placeholder="placeholder"
                    class="max-h-32 flex-1 resize-none rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-navy-900 placeholder-slate-400 transition focus:border-primary-500 focus:bg-white focus:ring-4 focus:ring-primary-500/10"
                    @keydown.enter.exact.prevent="submit"
                />

                <Button type="submit" :disabled="!text.trim() || sending" :loading="sending" class="shrink-0" aria-label="Send message">
                    <PaperAirplaneIcon class="h-4 w-4" />
                    <span class="hidden sm:inline">Send</span>
                </Button>
            </form>
        </div>
    </div>
</template>
