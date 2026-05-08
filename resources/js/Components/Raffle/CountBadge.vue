<script setup>
defineProps({
    text: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="rounded-full border border-[#f7931a]/30 bg-[#f7931a]/10 px-3 py-1 font-mono text-xs text-[#f7931a]">
        {{ text }}
    </div>
</template>
