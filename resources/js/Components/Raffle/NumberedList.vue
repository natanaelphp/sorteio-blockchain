<script setup>
defineProps({
    items: {
        type: Array,
        required: true,
    },
    hover: {
        type: Boolean,
        default: false,
    },
    listClass: {
        type: String,
        default: 'mt-8 space-y-3',
    },
});
</script>

<template>
    <ol :class="listClass">
        <li
            v-for="(item, index) in items"
            :key="item.id ?? `${item.title ?? item}-${index}`"
            class="flex gap-4 rounded-lg border border-zinc-800 bg-zinc-950/60 p-4 transition"
            :class="hover ? 'hover:border-[#f7931a]/50 hover:bg-zinc-950' : ''"
        >
            <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-[#f7931a]/40 bg-[#f7931a]/10 font-mono text-sm font-bold text-[#f7931a]">
                {{ index + 1 }}
            </span>

            <slot :item="item" :index="index" />
        </li>
    </ol>
</template>
