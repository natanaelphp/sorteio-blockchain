<script setup>
import { computed, ref, onMounted, onUnmounted, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { LoaderCircle, Zap, Trophy } from 'lucide-vue-next';
import CountBadge from '@/Components/Raffle/CountBadge.vue';
import HowItWorks from '@/Components/Raffle/HowItWorks.vue';
import Panel from '@/Components/Raffle/Panel.vue';
import RaffleLayout from '@/Layouts/RaffleLayout.vue';

const props = defineProps({
    raffle: {
        type: Object,
        required: true,
    },
});

// ── Fases da revelação ────────────────────────────────────────────────────────
const PHASES = ['waiting', 'block', 'hash', 'scanning', 'done'];
const phase = ref(props.raffle.status === 'done' ? 'done' : 'waiting');
const gte = (p) => PHASES.indexOf(phase.value) >= PHASES.indexOf(p);

const scanIndex = ref(-1);
let pollTimer = null
let scanTimer = null;
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

// ── Polling via Inertia ───────────────────────────────────────────────────────
onMounted(() => {
    if (phase.value !== 'done') {
        pollTimer = setInterval(() => router.reload({ only: ['raffle'] }), 3000);
    }
});

onUnmounted(() => {
    clearInterval(pollTimer);
    clearTimeout(scanTimer);
});

watch(() => props.raffle.status, (val) => {
    if (val === 'done') {
        clearInterval(pollTimer);
        startReveal();
    }
});

// ── Animação de revelação ─────────────────────────────────────────────────────
async function startReveal() {
    phase.value = 'block';
    await sleep(2000);

    phase.value = 'hash';
    await sleep(2500);

    phase.value = 'scanning';
    await runScan();

    phase.value = 'done';
}

function runScan() {
    return new Promise((resolve) => {
        const list = props.raffle.participants;
        const winnerIdx = list.indexOf(props.raffle.winner);
        const total = list.length * 2 + 8;
        let step = 0;
        let i = 0;

        (function tick() {
            scanIndex.value = i++ % list.length;
            const p = ++step / total;
            const delay = p > 0.8 ? 320 : p > 0.6 ? 160 : 70;
            if (step >= total) { scanIndex.value = winnerIdx; return resolve(); }
            scanTimer = setTimeout(tick, delay);
        })();
    });
}

// ── Participantes ─────────────────────────────────────────────────────────────
const participantFilter = ref('');

const normalize = (v) => v.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();

const filteredParticipants = computed(() => {
    const q = normalize(participantFilter.value.trim());
    return props.raffle.participants
        .map((participant, index) => ({ participant, index }))
        .filter(({ participant }) => normalize(participant).includes(q));
});

// ── Classes dinâmicas ─────────────────────────────────────────────────────────
const cardTitles = {
    waiting:  'Aguardando próximo bloco.',
    block:    'Novo bloco detectado!',
    hash:     'Novo bloco detectado!',
    scanning: 'Calculando vencedor...',
    done:     'Sorteio concluído!',
};

const itemClass = (participant, index) => {
    if (phase.value === 'scanning' && scanIndex.value === index)
        return 'border-[#f7931a] bg-[#f7931a]/10 scale-[1.02] shadow-md shadow-[#f7931a]/20';
    if (phase.value === 'done' && participant === props.raffle.winner)
        return 'border-yellow-400/50 bg-yellow-400/10';
    return 'border-zinc-800 bg-zinc-950/60';
};

const badgeClass = (participant) =>
    phase.value === 'done' && participant === props.raffle.winner
        ? 'border-yellow-400/40 bg-yellow-400/10 text-yellow-400'
        : 'border-[#f7931a]/40 bg-[#f7931a]/10 text-[#f7931a]';

const nameClass = (participant) =>
    phase.value === 'done' && participant === props.raffle.winner
        ? 'text-yellow-300 font-semibold'
        : 'text-zinc-200';
</script>

<template>
    <Head title="Acompanhar sorteio" />

    <RaffleLayout :status-label="raffle.status_label">
        <div class="space-y-8 py-14">
            <div>
                <p class="font-mono text-sm uppercase tracking-[0.28em] text-[#f7931a]">
                    Sorteio em andamento
                </p>

                <div class="mt-8 grid gap-4 lg:grid-cols-[1fr_24rem]">
                    <!-- Card principal com revelação progressiva -->
                    <div
                        :class="[
                            'rounded-2xl transition-all duration-700',
                            gte('block') && !gte('done')
                                ? 'ring-2 ring-[#f7931a]/60 shadow-[0_0_48px_rgba(247,147,26,0.25)]'
                                : '',
                        ]"
                    >
                        <Panel padding-class="p-6 h-full">
                            <div class="flex items-start gap-4">
                                <div
                                    v-if="phase != 'done'"
                                    :class="[
                                        'flex size-12 shrink-0 items-center justify-center rounded-full border transition-colors duration-500',
                                        gte('done')
                                            ? 'border-yellow-400/40 bg-yellow-400/10 text-yellow-400'
                                            : 'border-[#f7931a]/40 bg-[#f7931a]/10 text-[#f7931a]',
                                    ]"
                                >
                                    <LoaderCircle v-if="phase === 'waiting'" class="size-6 animate-spin" />
                                    <Zap v-else-if="!gte('done')" class="size-6" />
                                    <Trophy v-else class="size-6" />
                                </div>

                                <div class="flex-1 space-y-3">
                                    <h2 class="text-2xl font-semibold text-white">
                                        {{ cardTitles[phase] }}
                                    </h2>

                                    <p v-if="phase === 'waiting'" class="text-base leading-8 text-zinc-300">
                                        Assim que um novo bloco for minerado, o sorteio será realizado automaticamente com base no hash do bloco.
                                    </p>

                                    <Transition name="fade">
                                        <p v-if="gte('block')" class="font-mono text-sm text-[#f7931a]">
                                            ⚡ Bloco detectado na rede Bitcoin
                                        </p>
                                    </Transition>

                                    <Transition name="fade">
                                        <div v-if="gte('hash')" class="rounded-lg border border-emerald-500/20 bg-emerald-500/5 px-3 py-2">
                                            <p class="font-mono text-xs uppercase tracking-widest text-zinc-500">Hash do bloco</p>
                                            <p class="mt-1 break-all font-mono text-xs leading-5 text-emerald-400">
                                                {{ raffle.block_hash }}
                                            </p>
                                        </div>
                                    </Transition>

                                    <Transition name="fade">
                                        <p v-if="phase === 'scanning'" class="text-sm text-zinc-400">
                                            Procurando o vencedor ...
                                        </p>
                                    </Transition>

                                    <Transition name="fade">
                                        <p v-if="gte('done')" class="text-2xl font-bold text-yellow-300">
                                            🏆 {{ raffle.winner }}
                                        </p>
                                    </Transition>
                                </div>
                            </div>
                        </Panel>
                    </div>

                    <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                        <div class="rounded-lg border border-zinc-800 bg-zinc-950/60 p-4">
                            <dt class="font-mono text-xs uppercase tracking-[0.18em] text-zinc-500">Nome do sorteio</dt>
                            <dd class="mt-2 text-sm font-medium text-white">{{ raffle.title }}</dd>
                        </div>
                        <div class="rounded-lg border border-zinc-800 bg-zinc-950/60 p-4">
                            <dt class="font-mono text-xs uppercase tracking-[0.18em] text-zinc-500">Criado em</dt>
                            <dd class="mt-2 text-sm font-medium text-white">{{ raffle.created_at }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Como funciona a escolha do ganhador -->
            <HowItWorks :phase="phase" />

            <!-- Lista de participantes -->
            <Panel>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-mono text-xs uppercase tracking-[0.22em] text-zinc-400">Participantes</p>
                        <h2 class="text-2xl font-semibold text-white">Lista do sorteio</h2>
                    </div>
                    <CountBadge :text="`${raffle.participants.length} participantes`" />
                </div>

                <div class="mt-2">
                    <label for="participant-filter" class="sr-only">Buscar por participantes</label>
                    <input
                        id="participant-filter"
                        v-model="participantFilter"
                        type="text"
                        class="block w-full rounded-lg border-zinc-700 bg-black/35 px-4 py-3 text-sm text-white placeholder:text-zinc-600 focus:border-[#f7931a] focus:ring-[#f7931a]"
                        placeholder="Buscar por participantes"
                        autocomplete="off"
                    />
                </div>

                <ol v-if="filteredParticipants.length" class="mt-4 grid gap-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <li
                        v-for="{ participant, index } in filteredParticipants"
                        :key="`${participant}-${index}`"
                        :class="['flex min-w-0 items-center gap-2 rounded-md border px-3 py-2 transition-all duration-150', itemClass(participant, index)]"
                    >
                        <span :class="['flex size-6 shrink-0 items-center justify-center rounded-full border font-mono text-xs font-bold', badgeClass(participant)]">
                            {{ index + 1 }}
                        </span>
                        <span :class="['min-w-0 break-words text-sm font-medium leading-5', nameClass(participant)]">
                            {{ participant }}
                        </span>
                    </li>
                </ol>
                <p v-else class="mt-4 rounded-md border border-zinc-800 bg-zinc-950/60 px-3 py-2 text-sm text-zinc-500">
                    Nenhum participante encontrado.
                </p>
            </Panel>
        </div>
    </RaffleLayout>
</template>

<style scoped>
.fade-enter-active {
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.fade-enter-from {
    opacity: 0;
    transform: translateY(6px);
}
</style>
