<?php

namespace App\Domain\Entity;

use Exception;

class Raffle
{
    public function __construct(
        private string $title,
        private array $participants,
        private string $status,
        private ?string $blockHash = null,
        private ?string $winner = null,
        private ?int $id = null,
    ) {
    }

    public function draw(string $blockHash): string
    {
        if ($this->status !== 'waiting') {
            throw new Exception('Raffle is not waiting');
        }

        $this->status = 'done';
        $this->blockHash = $blockHash;

        $number = hexdec(substr($blockHash, -8));
        $participantsCount = count($this->participants);

        $winnerIndex = ($number % $participantsCount);

        $this->winner = $this->participants[$winnerIndex];

        return $this->winner;
    }

    public function getTitle(): string  
    {
        return $this->title;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getBlockHash(): ?string
    {
        return $this->blockHash;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function getId(): int
    {
        return $this->id;
    }
}