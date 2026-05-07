<?php

namespace App\Console\Commands;

use ZMQ;
use ZMQContext;
use Illuminate\Console\Command;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Description;

#[Signature('app:listen-bitcoin-blocks')]
#[Description('Receber blocos da rede bitcoin vie ZMQ')]
class ListenBitcoinBlocks extends Command
{
    public function handle()
    {
        $context = new ZMQContext();

        $socket = $context->getSocket(ZMQ::SOCKET_SUB);

        $socket->connect("tcp://127.0.0.1:28332");
        $socket->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "hashblock");

        $this->info("Listening for blocks...");

        while (true) {
            $message = $socket->recvMulti();

            $topic = $message[0];
            $body = $message[1];

            $hash = bin2hex($body);

            $this->newLine();
            $this->info("New block detected!");
            $this->line("Topic: {$topic}");
            $this->line("Hash: {$hash}");
        }
    }
}
