<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\NetworkResource;
use App\Models\Network;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class NetworksController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Network::class);

        $user = type($request->user())->as(User::class);

        $networks = Network::query()
            ->visibleTo($user)
            ->with('manager:id,name')
            ->withBalance()
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Networks/Index', [
            'networks' => NetworkResource::collection($networks),
            'can' => [
                'create' => Gate::allows('create', Network::class),
                'update' => Gate::allows('update', Network::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Network::class);

        return Inertia::render('Networks/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Network::class);

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
    public function show(Network $network): Response
    {
        Gate::authorize('view', $network);

        $network->load('partners:id,name', 'manager:id,name');

        return Inertia::render('Networks/Show', [
            'network' => NetworkResource::single($network),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Network $network): Response
    {
        Gate::authorize('update', Network::class);

        $network->load('partners:id,name', 'manager:id,name');

        return Inertia::render('Networks/Edit', [
            'network' => NetworkResource::single($network),
            'can' => [
                'assignManager' => Gate::allows('assignManager', Network::class),
                'createPartner' => Gate::allows('createPartner', Network::class),
                'deletePartner' => Gate::allows('deletePartner', Network::class),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Network $network)
    {
        Gate::authorize('update', Network::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', Rule::unique('networks', 'name')->ignore($network->id)],
            'internet_price_per_week' => ['nullable', 'numeric'],
        ]);

        $network->update($validated);

        return back();
    }
}
