<?php

namespace App\Infra\Repository;

use App\Domain\Entity\Raffle;
use App\Models\Raffle as RaffleModel;
use App\Domain\Repository\RaffleRepositoryInterface;

class RaffleRepository implements RaffleRepositoryInterface
{
    public function __construct(
        private RaffleModel $model
    ) {
    }

    public function getWaitingRaffles(): array
    {
        $collection = $this->model->where('status', 'waiting')->get();

        return $collection->map->toEntity()->all();
    }
    
    public function update(Raffle $raffle): bool
    {
        return $this->model->find($raffle->getId())->update([
            'status' => $raffle->getStatus(),
            'block_hash' => $raffle->getBlockHash(),
            'winner' => $raffle->getWinner(),
        ]);
    }
}
