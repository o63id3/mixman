<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Region;
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
            ->with('region:id,name')
            ->paginate(10);

        return Inertia::render('Sellers/Index', [
            'sellers' => UserResource::collection($sellers),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', User::class);

        return Inertia::render('Sellers/Create', [
            'regions' => Region::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'region' => ['required', Rule::exists('regions', 'id')],
            'username' => ['required', 'string', 'min:2', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:4'],
            'contact_info' => ['string'],
            'notes' => ['string'],
        ]);

        $validated['region_id'] = $validated['region'];

        unset($validated['region']);

        User::create($validated);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $seller): Response
    {
        Gate::authorize('update', User::class);

        $seller->load('region');

        return Inertia::render('Sellers/Edit', [
            'seller' => UserResource::make($seller),
            'regions' => Region::all(),
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
            'region' => ['required', Rule::exists('regions', 'id')],
            'username' => ['required', 'string', 'min:2', Rule::unique('users', 'username')->ignore($seller->id)],
            'password' => ['string', 'min:4'],
            'contact_info' => ['string'],
            'notes' => ['string'],
        ]);

        $validated['region_id'] = $validated['region'];
        unset($validated['region']);

        $seller->update($validated);

        return back();
    }
}
