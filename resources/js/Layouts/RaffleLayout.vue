<script setup>
defineProps({
    statusLabel: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <main class="min-h-screen bg-[#070707] text-zinc-100">
        <section class="relative isolate min-h-screen overflow-hidden border-b border-zinc-800/80">
            <div class="absolute -right-72 -top-72 -z-10 size-[80rem] bg-[radial-gradient(circle,rgba(247,147,26,0.24)_0%,transparent_68%)]"></div>

            <div class="mx-auto max-w-6xl px-6 py-8 lg:px-8 lg:py-12">
                <header class="flex items-center justify-between gap-6">
                    <div class="flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center rounded-full border border-[#f7931a]/40 bg-[#f7931a]/10 text-lg font-bold text-[#f7931a]">
                            ₿
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase tracking-[0.32em] text-[#f7931a]">
                                Bitcoin raffle
                            </p>
                            <p class="text-sm text-zinc-400">
                                Aleatoriedade verificável usando a blockchain do Bitcoin
                            </p>
                        </div>
                    </div>

                    <div
                        v-if="statusLabel"
                        class="hidden rounded-full border border-[#f7931a]/30 bg-[#f7931a]/10 px-4 py-2 font-mono text-xs uppercase tracking-[0.22em] text-[#f7931a] sm:block"
                    >
                        {{ statusLabel }}
                    </div>
                </header>

                <slot />
            </div>
        </section>
    </main>
</template>
