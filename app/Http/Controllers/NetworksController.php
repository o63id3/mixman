<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\NetworkResource;
use App\Models\Network;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class NetworksController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        // Gate::authorize('viewAny', User::class);

        $networks = Network::query()
            // ->whereNot('role', RoleEnum::Seller)
            ->with('manager:id,name')
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Networks/Index', [
            'networks' => NetworkResource::collection($networks),
            'can' => [
                'create' => auth()->user()->isAhmed(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        // Gate::authorize('create', Region::class);

        return Inertia::render('Networks/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Gate::authorize('create', Region::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', Rule::unique('networks', 'name')],
            'internet_price_per_week' => ['numeric'],
        ]);

        Network::create($validated);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Network $network)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Network $network): Response
    {
        // Gate::authorize('update', Region::class);

        $network->load('partners:id,name', 'manager:id,name');

        return Inertia::render('Networks/Edit', [
            'network' => NetworkResource::make($network),
            'can' => [
                'createPartner' => auth()->user()->isAhmed(),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Network $network)
    {
        // Gate::authorize('create', Region::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', Rule::unique('networks', 'name')->ignore($network->id)],
            'internet_price_per_week' => ['nullable', 'numeric'],
        ]);

        $network->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Network $network)
    {
        //
    }
}
