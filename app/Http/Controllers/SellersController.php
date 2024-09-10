<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class SellersController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', User::class);

        $sellers = User::query()
            ->sellers()
            ->latest()
            ->paginate(10);

        return Inertia::render('Sellers/Index', [
            'sellers' => $sellers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', User::class);

        return Inertia::render('Sellers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'username' => ['required', 'string', 'min:2', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:4'],
            'contact_info' => ['string'],
            'notes' => ['string'],
        ]);

        User::create($validated);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $seller): Response
    {
        Gate::authorize('update', User::class);

        return Inertia::render('Sellers/Edit', [
            'seller' => $seller,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $seller): RedirectResponse
    {
        Gate::authorize('update', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'username' => ['required', 'string', 'min:2', Rule::unique('users', 'username')->ignore($seller->id)],
            'password' => ['string', 'min:4'],
            'contact_info' => ['string'],
            'notes' => ['string'],
        ]);

        $seller->update($validated);

        return back();
    }
}
