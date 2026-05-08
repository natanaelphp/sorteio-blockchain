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
        return Inertia::render('Raffle/Show', [
            'id' => $id,
        ]);
    }
}
