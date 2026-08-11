<script setup>
const emit = defineEmits(['select', 'close']);

const EMOJIS = [
    '😀', '😁', '😂', '🤣', '😊', '😇', '🙂', '😉', '😍', '🤩',
    '😘', '😋', '😜', '🤪', '🤗', '🤔', '🤭', '😐', '😴', '🥱',
    '😢', '😭', '😤', '😡', '🤬', '🥰', '😎', '🤓', '👻', '💀',
    '👍', '👎', '👌', '✌️', '🤞', '🤝', '👏', '🙏', '💪', '🤙',
    '🫶', '❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '💖', '💔',
    '✨', '🎉', '🎊', '🥳', '🎂', '🎁', '🌸', '🌹', '☀️', '🌙',
    '⚡', '🔥', '💯', '✅', '❌', '❓', '❗', '💡', '📞', '📷',
    '💊', '🏥', '⚕️', '🚑', '💉', '🩺', '👨‍⚕️', '👩‍⚕️', '🤒', '🤕',
];
</script>

<template>
    <div class="absolute bottom-full right-0 z-20 mb-2 w-64 rounded-2xl border border-slate-200/80 bg-white p-3 shadow-xl">
        <div class="grid max-h-44 grid-cols-8 gap-1 overflow-y-auto scrollbar-thin">
            <button
                v-for="emoji in EMOJIS"
                :key="emoji"
                type="button"
                class="flex h-7 w-7 items-center justify-center rounded-lg text-lg transition hover:bg-slate-100"
                @click="emit('select', emoji)"
            >
                {{ emoji }}
            </button>
        </div>
    </div>
</template>
