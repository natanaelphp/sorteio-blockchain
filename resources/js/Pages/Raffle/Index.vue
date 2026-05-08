<script setup>
import { computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    participants: '',
});

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

    <main class="min-h-screen bg-[#070707] text-zinc-100">
        <section class="relative isolate overflow-hidden border-b border-zinc-800/80">
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

                    <!-- <div class="hidden rounded-full border border-zinc-800 bg-zinc-950/70 px-4 py-2 font-mono text-xs uppercase tracking-[0.22em] text-zinc-400 sm:block">
                        Status inicial: waiting
                    </div> -->
                </header>

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

                        <ol class="mt-8 space-y-3">
                            <li class="group flex gap-4 rounded-lg border border-zinc-800 bg-zinc-950/60 p-4 transition hover:border-[#f7931a]/50 hover:bg-zinc-950">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-[#f7931a]/40 bg-[#f7931a]/10 font-mono text-sm font-bold text-[#f7931a]">
                                    1
                                </span>
                                <div>
                                    <h3 class="font-medium text-white">
                                        Você cria o sorteio
                                    </h3>
                                    <p class="mt-1 text-sm leading-6 text-zinc-400">
                                        Informe o nome e cole a lista de participantes, um por linha.
                                    </p>
                                </div>
                            </li>

                            <li class="group flex gap-4 rounded-lg border border-zinc-800 bg-zinc-950/60 p-4 transition hover:border-[#f7931a]/50 hover:bg-zinc-950">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-[#f7931a]/40 bg-[#f7931a]/10 font-mono text-sm font-bold text-[#f7931a]">
                                    2
                                </span>
                                <div>
                                    <h3 class="font-medium text-white">
                                        O sistema aguarda o próximo bloco
                                    </h3>
                                    <p class="mt-1 text-sm leading-6 text-zinc-400">
                                        O sorteio fica pendente até um novo bloco Bitcoin ser minerado.
                                    </p>
                                </div>
                            </li>

                            <li class="group flex gap-4 rounded-lg border border-zinc-800 bg-zinc-950/60 p-4 transition hover:border-[#f7931a]/50 hover:bg-zinc-950">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-[#f7931a]/40 bg-[#f7931a]/10 font-mono text-sm font-bold text-[#f7931a]">
                                    3
                                </span>
                                <div>
                                    <h3 class="font-medium text-white">
                                        O hash define o vencedor
                                    </h3>
                                    <p class="mt-1 text-sm leading-6 text-zinc-400">
                                        O hash do bloco é usado para escolher uma posição da lista.
                                    </p>
                                </div>
                            </li>

                            <li class="group flex gap-4 rounded-lg border border-zinc-800 bg-zinc-950/60 p-4 transition hover:border-[#f7931a]/50 hover:bg-zinc-950">
                                <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-[#f7931a]/40 bg-[#f7931a]/10 font-mono text-sm font-bold text-[#f7931a]">
                                    4
                                </span>
                                <div>
                                    <h3 class="font-medium text-white">
                                        Qualquer pessoa verifica
                                    </h3>
                                    <p class="mt-1 text-sm leading-6 text-zinc-400">
                                        O resultado pode ser conferido publicamente pela blockchain.
                                    </p>
                                </div>
                            </li>
                        </ol>

                        <!-- <dl class="mt-10 grid gap-4 sm:grid-cols-3 lg:max-w-lg">
                            <div class="border-l border-[#f7931a]/60 pl-4">
                                <dt class="font-mono text-xs uppercase tracking-[0.18em] text-zinc-500">
                                    Rede
                                </dt>
                                <dd class="mt-2 text-sm font-medium text-white">
                                    Bitcoin
                                </dd>
                            </div>
                            <div class="border-l border-[#f7931a]/60 pl-4">
                                <dt class="font-mono text-xs uppercase tracking-[0.18em] text-zinc-500">
                                    Entrada
                                </dt>
                                <dd class="mt-2 text-sm font-medium text-white">
                                    Lista de participantes
                                </dd>
                            </div>
                            <div class="border-l border-[#f7931a]/60 pl-4">
                                <dt class="font-mono text-xs uppercase tracking-[0.18em] text-zinc-500">
                                    Resultado
                                </dt>
                                <dd class="mt-2 text-sm font-medium text-white">
                                    Hash do bloco
                                </dd>
                            </div>
                        </dl> -->
                    </div>

                    <form
                        class="rounded-2xl border border-zinc-800 bg-[#111111] p-5 shadow-2xl shadow-black/40 sm:p-7"
                        @submit.prevent="submit"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-semibold text-white">
                                    Crie seu sorteio
                                </h2>
                            </div>

                            <div class="rounded-full border border-[#f7931a]/30 bg-[#f7931a]/10 px-3 py-1 font-mono text-xs text-[#f7931a]">
                                {{ participants.length }} participantes
                            </div>
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
                            <div class="flex items-center justify-between gap-4">
                                <p class="font-mono text-xs uppercase tracking-[0.22em] text-zinc-500">
                                    Prévia dos participantes
                                </p>
                            </div>

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
                    </form>
                </div>
            </div>
        </section>
    </main>
</template>
