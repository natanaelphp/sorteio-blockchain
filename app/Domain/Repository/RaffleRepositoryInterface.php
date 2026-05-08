<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Raffle;

interface RaffleRepositoryInterface
{
    public function getWaitingRaffles(): array;

    public function update(Raffle $raffle): bool;
}