<script setup>
import { ref, watch } from 'vue';
import { Box, Hash, Calculator, ChevronDown, ChevronUp } from 'lucide-vue-next';
import Panel from '@/Components/Raffle/Panel.vue';

const props = defineProps({
    phase: {
        type: String,
        default: 'waiting',
    },
});

const showExplanation = ref(true);

watch(() => props.phase, (newPhase) => {
    if (newPhase !== 'waiting') {
        showExplanation.value = false;
    }
});
</script>

<template>
    <Panel :padding-class="showExplanation ? 'p-5 sm:p-7 transition-all duration-300' : 'p-3 px-4 sm:p-4 sm:px-5 transition-all duration-300'">
        <div 
            class="flex cursor-pointer items-center justify-between gap-4 select-none group" 
            @click="showExplanation = !showExplanation"
        >
            <p class="font-mono text-xs uppercase tracking-[0.22em] text-zinc-400">Como funciona a escolha do ganhador</p>
            <button class="flex shrink-0 items-center gap-2 rounded-lg border border-zinc-800 bg-zinc-950/60 px-3 py-1.5 text-sm font-medium text-zinc-300 transition-colors group-hover:bg-zinc-900 group-hover:text-white">
                <span v-if="showExplanation">Ocultar explicação</span>
                <span v-else>Ver explicação</span>
                <ChevronUp v-if="showExplanation" class="size-4" />
                <ChevronDown v-else class="size-4" />
            </button>
        </div>

        <Transition
            enter-active-class="transition-all duration-200 ease-out"
            enter-from-class="opacity-0 -translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-100 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
            <div v-show="showExplanation" class="mt-8 grid gap-6 md:grid-cols-3">
                <div class="flex flex-col gap-4 rounded-xl border border-zinc-800 bg-zinc-950/40 p-6 transition-colors hover:border-[#f7931a]/50 hover:bg-zinc-900/50">
                    <div class="flex size-12 items-center justify-center rounded-lg border border-[#f7931a]/30 bg-[#f7931a]/10 text-[#f7931a] shadow-[0_0_15px_rgba(247,147,26,0.15)]">
                        <Box class="size-6" />
                    </div>
                    <div>
                        <h3 class="font-medium text-white">1. O Bloco Bitcoin</h3>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-400">
                            Aguardamos a mineração de um novo bloco na rede Bitcoin. Isso garante que o evento base seja totalmente descentralizado e impossível de prever antes de ser publicado.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-4 rounded-xl border border-zinc-800 bg-zinc-950/40 p-6 transition-colors hover:border-emerald-500/50 hover:bg-zinc-900/50">
                    <div class="flex size-12 items-center justify-center rounded-lg border border-emerald-500/30 bg-emerald-500/10 text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                        <Hash class="size-6" />
                    </div>
                    <div>
                        <h3 class="font-medium text-white">2. Processando o Hash</h3>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-400">
                            Quando o bloco é minerado, capturamos seu hash único. Extraímos apenas os <strong>últimos 8 caracteres hexadecimais</strong> desse hash e os convertemos para um número inteiro decimal.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-4 rounded-xl border border-zinc-800 bg-zinc-950/40 p-6 transition-colors hover:border-indigo-500/50 hover:bg-zinc-900/50">
                    <div class="flex size-12 items-center justify-center rounded-lg border border-indigo-500/30 bg-indigo-500/10 text-indigo-400 shadow-[0_0_15px_rgba(99,102,241,0.15)]">
                        <Calculator class="size-6" />
                    </div>
                    <div>
                        <h3 class="font-medium text-white">3. A Matemática</h3>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-400">
                            Pegamos o número convertido e dividimos pelo <strong>total de participantes</strong> no sorteio. O <strong>resto dessa divisão</strong> (operação de módulo) aponta com exatidão qual é a posição do vencedor na nossa lista.
                        </p>
                    </div>
                </div>
            </div>
        </Transition>
    </Panel>
</template>
