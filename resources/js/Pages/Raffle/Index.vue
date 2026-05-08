<script setup>
import { computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import CountBadge from '@/Components/Raffle/CountBadge.vue';
import NumberedList from '@/Components/Raffle/NumberedList.vue';
import Panel from '@/Components/Raffle/Panel.vue';
import RaffleLayout from '@/Layouts/RaffleLayout.vue';

const form = useForm({
    title: '',
    participants: '',
});

const raffleSteps = [
    {
        title: 'Você cria o sorteio',
        description: 'Informe o nome e cole a lista de participantes, um por linha.',
    },
    {
        title: 'O sistema aguarda o próximo bloco',
        description: 'O sorteio fica pendente até um novo bloco Bitcoin ser minerado.',
    },
    {
        title: 'O hash define o vencedor',
        description: 'O hash do bloco é usado para escolher uma posição da lista.',
    },
    {
        title: 'Qualquer pessoa verifica',
        description: 'O resultado pode ser conferido publicamente pela blockchain.',
    },
];

const participants = computed(() =>
    form.participants
        .split('\n')
        .map((participant) => participant.trim())
        .filter(Boolean),
);

const firstErrorFor = (field) => {
    const nestedField = Object.keys(form.errors).find((errorField) =>
        errorField.startsWith(`${field}.`),
    );

    return form.errors[field] || (nestedField ? form.errors[nestedField] : null);
};

const participantsError = computed(() => firstErrorFor('participants'));

const submit = () => {
    form.transform((data) => ({
        title: data.title,
        participants: participants.value,
    })).post(route('raffle.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Criar Sorteio" />

    <RaffleLayout>
        <div class="grid gap-10 py-14 lg:grid-cols-[0.95fr_1.05fr]">
            <div class="max-w-xl">
                <p class="font-mono text-sm uppercase tracking-[0.28em] text-[#f7931a]">
                    Sorteio verificável
                </p>
                <h1 class="mt-5 text-4xl font-semibold leading-tight text-white sm:text-5xl">
                    Um sorteio verificável baseado na blockchain do Bitcoin
                </h1>
                <p class="mt-6 text-base leading-8 text-zinc-300">
                    O vencedor é definido utilizando o hash do próximo bloco minerado na rede Bitcoin.
                </p>

                <NumberedList :items="raffleSteps" hover>
                    <template #default="{ item }">
                        <div>
                            <h3 class="font-medium text-white">
                                {{ item.title }}
                            </h3>
                            <p class="mt-1 text-sm leading-6 text-zinc-400">
                                {{ item.description }}
                            </p>
                        </div>
                    </template>
                </NumberedList>
            </div>

            <Panel as="form" @submit.prevent="submit">
                <div class="flex items-start justify-between gap-4">
                    <h2 class="text-2xl font-semibold text-white">
                        Crie seu sorteio
                    </h2>

                    <CountBadge :text="`${participants.length} participantes`" />
                </div>

                <div class="mt-8 space-y-6">
                    <div>
                        <label for="title" class="block font-mono text-xs uppercase tracking-[0.22em] text-zinc-400">
                            Nome
                        </label>
                        <input
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="mt-3 block w-full rounded-lg border-zinc-700 bg-black/35 px-4 py-3 text-white placeholder:text-zinc-600 focus:border-[#f7931a] focus:ring-[#f7931a]"
                            placeholder="Ex: Sorteio CoreCraft #1"
                            autocomplete="off"
                        />
                        <p v-if="form.errors.title" class="mt-2 text-sm text-red-400">
                            {{ form.errors.title }}
                        </p>
                    </div>

                    <div>
                        <label for="participants" class="block font-mono text-xs uppercase tracking-[0.22em] text-zinc-400">
                            Participantes
                        </label>
                        <textarea
                            id="participants"
                            v-model="form.participants"
                            rows="9"
                            class="mt-3 block w-full resize-y rounded-lg border-zinc-700 bg-black/35 px-4 py-3 text-white placeholder:text-zinc-600 focus:border-[#f7931a] focus:ring-[#f7931a]"
                            placeholder="Um participante por linha&#10;Alice&#10;Bob&#10;Carol"
                        ></textarea>
                        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-sm">
                            <p class="text-zinc-500">Um participante por linha.</p>

                            <p v-if="participantsError" class="text-red-400">
                                {{ participantsError }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 rounded-lg border border-zinc-800 bg-black/25 p-4">
                    <p class="font-mono text-xs uppercase tracking-[0.22em] text-zinc-500">
                        Prévia dos participantes
                    </p>

                    <div v-if="participants.length" class="mt-4 flex flex-wrap gap-2">
                        <span
                            v-for="participant in participants"
                            :key="participant"
                            class="rounded-lg border border-zinc-700 bg-zinc-900 px-3 py-1 text-sm text-zinc-200"
                        >
                            {{ participant }}
                        </span>
                    </div>
                    <p v-else class="mt-3 text-sm text-zinc-600">
                        A lista aparece aqui para você verificar se está criando corretamente.
                    </p>
                </div>

                <button
                    type="submit"
                    class="mt-8 inline-flex w-full items-center justify-center rounded-lg border border-[#f7931a] bg-[#f7931a] px-5 py-3 text-sm font-bold uppercase tracking-[0.18em] text-black transition hover:bg-[#ffad42] focus:outline-none focus:ring-2 focus:ring-[#f7931a] focus:ring-offset-2 focus:ring-offset-zinc-950 disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Criando sorteio...' : 'Criar sorteio' }}
                </button>
            </Panel>
        </div>
    </RaffleLayout>
</template>
