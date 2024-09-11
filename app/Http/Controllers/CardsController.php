<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class CardsController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Card::class);

        $cards = Card::query()
            ->latest()
            ->paginate(10);

        return Inertia::render('Cards/Index', [
            'cards' => $cards,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Card::class);

        return Inertia::render('Cards/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Card::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', Rule::unique('cards', 'name')],
            'price' => ['required', 'numeric'],
            'notes' => ['string'],
        ]);

        Card::create($validated);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card): Response
    {
        Gate::authorize('update', Card::class);

        return Inertia::render('Cards/Edit', [
            'card' => $card,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card): RedirectResponse
    {
        Gate::authorize('update', Card::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', Rule::unique('cards', 'name')->ignore($card->id)],
            'price' => ['required', 'numeric'],
            'active' => ['boolean'],
            'notes' => ['string'],
        ]);

        $card->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        //
    }
}
