<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\NetworkResource;
use App\Models\Network;
use Illuminate\Http\Request;
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
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(Network $network)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Network $network)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Network $network)
    {
        //
    }
}
