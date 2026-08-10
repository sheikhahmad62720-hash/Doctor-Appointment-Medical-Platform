<script setup>
import {
    ClipboardDocumentCheckIcon,
    ArrowPathIcon,
    HeartIcon,
    ShieldCheckIcon,
    BeakerIcon,
    VideoCameraIcon,
    SparklesIcon,
    ClipboardDocumentListIcon,
    ScaleIcon,
    ScissorsIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    name: { type: String, default: 'stethoscope' },
    class: { type: [String, Object, Array], default: '' },
});

const icons = {
    stethoscope: ClipboardDocumentCheckIcon,
    refresh: ArrowPathIcon,
    heart: HeartIcon,
    shield: ShieldCheckIcon,
    syringe: BeakerIcon,
    video: VideoCameraIcon,
    sparkles: SparklesIcon,
    clipboard: ClipboardDocumentListIcon,
    scale: ScaleIcon,
    scissors: ScissorsIcon,
};
</script>

<template>
    <component :is="icons[props.name] || ClipboardDocumentCheckIcon" :class="class" />
</template>
