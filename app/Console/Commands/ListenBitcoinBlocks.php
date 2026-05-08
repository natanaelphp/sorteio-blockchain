<?php

namespace App\Console\Commands;

use ZMQ;
use ZMQContext;
use Illuminate\Console\Command;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Description;
use App\Domain\Repository\RaffleRepositoryInterface;

#[Signature('app:listen-bitcoin-blocks')]
#[Description('Receber blocos da rede bitcoin vie ZMQ')]
class ListenBitcoinBlocks extends Command
{
    public function handle(RaffleRepositoryInterface $raffleRepository)
    {
        $context = new ZMQContext();

        $socket = $context->getSocket(ZMQ::SOCKET_SUB);

        $socket->connect(env('BITCOIN_ZMQ_HOST'));
        $socket->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "hashblock");

        $this->info("Aguardando blocos...");

        while (true) {
            $message = $socket->recvMulti();

            $topic = $message[0];
            $body = $message[1];

            $hash = bin2hex($body);

            $this->newLine();
            $this->line("Novo bloco detectado!");
            $this->line("Tópico: {$topic}");
            $this->line("Hash: {$hash}");
            $this->newLine();

            $raffles = $raffleRepository->getWaitingRaffles();

            if (count($raffles) == 0) {
                $this->info('Nenhum sorteio a ser realizado.');
            }

            foreach($raffles as $raffle) {
                $this->info("Sorteando: {$raffle->getTitle()}");
                $this->info("Ganhador: {$raffle->draw($hash)}");

                $raffleRepository->update($raffle);

                $this->newLine();
            }
        }
    }
}
