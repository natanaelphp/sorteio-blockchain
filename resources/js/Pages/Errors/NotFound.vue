<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Home } from 'lucide-vue-next';
import RaffleLayout from '@/Layouts/RaffleLayout.vue';
</script>

<template>
    <Head title="Página não encontrada" />

    <RaffleLayout status-label="404">
        <div class="absolute inset-0 flex items-center justify-center px-6 text-center">
            <section class="max-w-xl">
                <p class="font-mono text-sm uppercase tracking-[0.28em] text-[#f7931a]">
                    Erro 404
                </p>

                <h1 class="mt-5 text-4xl font-semibold leading-tight text-white sm:text-5xl">
                    Página não encontrada
                </h1>

                <Link
                    :href="route('raffle.create')"
                    class="mt-8 inline-flex items-center justify-center gap-2 rounded-lg border border-[#f7931a] bg-[#f7931a] px-5 py-3 text-sm font-bold uppercase tracking-[0.18em] text-black transition hover:bg-[#ffad42] focus:outline-none focus:ring-2 focus:ring-[#f7931a] focus:ring-offset-2 focus:ring-offset-zinc-950"
                >
                    <Home class="size-4" />
                    Criar sorteio
                </Link>
            </section>
        </div>
    </RaffleLayout>
</template>
