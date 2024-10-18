<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\CardResource;
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
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Cards/Index', [
            'cards' => CardResource::collection($cards),
            'can' => [
                'create' => Gate::allows('create', Card::class),
            ],
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
            'name' => ['required', 'unique:cards,name', 'string', 'min:2'],
            'price_for_consumer' => ['required', 'numeric'],
            'notes' => ['nullable', 'string'],
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
            'card' => CardResource::single($card),
            'can' => [
                'delete' => Gate::allows('delete', Card::class),
            ],
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
            'price_for_consumer' => ['required', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

        $card->update($validated);

        return back();
    }
}
