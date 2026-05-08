<script setup>
defineProps({
    as: {
        type: String,
        default: 'div',
    },
    paddingClass: {
        type: String,
        default: 'p-5 sm:p-7',
    },
});
</script>

<template>
    <component
        :is="as"
        :class="[
            'rounded-2xl border border-zinc-800 bg-[#111111] shadow-2xl shadow-black/40',
            paddingClass,
        ]"
    >
        <slot />
    </component>
</template>
