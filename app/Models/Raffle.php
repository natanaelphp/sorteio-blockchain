<?php

namespace App\Models;

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
}
