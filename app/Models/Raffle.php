<?php

namespace App\Models;

use App\Domain\Entity\Raffle as Entity;
use Illuminate\Database\Eloquent\Model;

class Raffle extends Model
{
    protected $table = 'raffle';

    protected $fillable = [
        'title',
        'participants',
        'status',
        'block_hash',
        'winner',
    ];

    protected function casts(): array
    {
        return [
            'participants' => 'array',
        ];
    }

    public function toEntity(): Entity
    {
        return new Entity(
            id: $this->id,
            title: $this->title,
            participants: $this->participants,
            status: $this->status,
            blockHash: $this->block_hash,
            winner: $this->winner,
        );
    }
}
