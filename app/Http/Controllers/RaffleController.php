<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Raffle;
use App\Http\Requests\RaffleRequest;
use Illuminate\Http\RedirectResponse;

class RaffleController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Raffle/Index');
    }

    public function store(RaffleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $raffle = Raffle::create([
            'title' => $validated['title'],
            'participants' => $validated['participants'],
            'status' => 'waiting',
        ]);

        return redirect()->route('raffle.show', $raffle);
    }

    public function show(string $id): Response
    {
        $raffle = Raffle::findOrFail($id);

        return Inertia::render('Raffle/Show', [
            'raffle' => [
                'id' => $raffle->id,
                'title' => $raffle->title,
                'participants' => $raffle->participants,
                'status' => $raffle->status,
                'status_label' => $raffle->status === 'waiting' ? 'Aguardando bloco' : 'Concluído',
                'created_at' => $raffle->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }
}
