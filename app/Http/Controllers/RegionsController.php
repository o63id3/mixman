<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class RegionsController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Region::class);

        $regions = Region::query()
            ->latest()
            ->paginate(10);

        return Inertia::render('Regions/Index', [
            'regions' => $regions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Region::class);

        return Inertia::render('Regions/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Region::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', Rule::unique('regions', 'name')],
        ]);

        Region::create($validated);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region): Response
    {
        Gate::authorize('update', Region::class);

        return Inertia::render('Regions/Edit', [
            'region' => $region,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region)
    {
        Gate::authorize('update', Region::class);

        $validated = $request->validate([
            'name' => ['string', 'min:2', Rule::unique('regions', 'name')->ignore($region->id)],
        ]);

        $region->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        //
    }
}
