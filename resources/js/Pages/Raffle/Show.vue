<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import CountBadge from '@/Components/Raffle/CountBadge.vue';
import Panel from '@/Components/Raffle/Panel.vue';
import RaffleLayout from '@/Layouts/RaffleLayout.vue';

const props = defineProps({
    raffle: {
        type: Object,
        required: true,
    },
});

const participantFilter = ref('');

const normalizeParticipant = (value) =>
    value
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();

const filteredParticipants = computed(() => {
    const search = normalizeParticipant(participantFilter.value.trim());

    return props.raffle.participants
        .map((participant, index) => ({ participant, index }))
        .filter(({ participant }) => normalizeParticipant(participant).includes(search));
});
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
                    <Panel padding-class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex size-12 shrink-0 items-center justify-center rounded-full border border-[#f7931a]/40 bg-[#f7931a]/10 text-[#f7931a]">
                                <LoaderCircle class="size-6 animate-spin" />
                            </div>

                            <div>
                                <h2 class="text-2xl font-semibold text-white">
                                    Aguardando próximo bloco.
                                </h2>
                                <p class="mt-4 text-base leading-8 text-zinc-300">
                                    Assim que um novo bloco for minerado, o sorteio será realizado automaticamente com base no hash do bloco.
                                </p>
                            </div>
                        </div>
                    </Panel>

                    <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                        <div class="rounded-lg border border-zinc-800 bg-zinc-950/60 p-4">
                            <dt class="font-mono text-xs uppercase tracking-[0.18em] text-zinc-500">
                                Nome do sorteio
                            </dt>
                            <dd class="mt-2 text-sm font-medium text-white">
                                {{ raffle.title }}
                            </dd>
                        </div>
                        <div class="rounded-lg border border-zinc-800 bg-zinc-950/60 p-4">
                            <dt class="font-mono text-xs uppercase tracking-[0.18em] text-zinc-500">
                                Criado em
                            </dt>
                            <dd class="mt-2 text-sm font-medium text-white">
                                {{ raffle.created_at }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <Panel>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-mono text-xs uppercase tracking-[0.22em] text-zinc-400">
                            Participantes
                        </p>
                        <h2 class="text-2xl font-semibold text-white">
                            Lista do sorteio
                        </h2>
                    </div>

                    <CountBadge :text="`${raffle.participants.length} participantes`" />
                </div>

                <div class="mt-2">
                    <label for="participant-filter" class="sr-only">
                        Buscar por participantes
                    </label>
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
                        class="flex min-w-0 items-center gap-2 rounded-md border border-zinc-800 bg-zinc-950/60 px-3 py-2"
                    >
                        <span class="flex size-6 shrink-0 items-center justify-center rounded-full border border-[#f7931a]/40 bg-[#f7931a]/10 font-mono text-xs font-bold text-[#f7931a]">
                            {{ index + 1 }}
                        </span>

                        <span class="min-w-0 break-words text-sm font-medium leading-5 text-zinc-200">
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
