<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Resources\NetworkResource;
use App\Models\Network;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

final class NetworkPartnersController
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Network $network): Response
    {
        Gate::authorize('createPartner', Network::class);

        return Inertia::render('NetworkPartners/Create', [
            'network' => NetworkResource::make($network),
            'partners' => User::whereNot('role', RoleEnum::Seller)->whereNotIn('id', $network->partners->pluck('id'))->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Network $network): RedirectResponse
    {
        Gate::authorize('createPartner', Network::class);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'share' => ['numeric'],
        ]);

        $network
            ->partners()
            ->attach($validated['user_id'], [
                'share' => $validated['share'] / 100,
            ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Network $network, User $partner): RedirectResponse
    {
        Gate::authorize('deletePartner', Network::class);

        if ($network->manager?->is($partner)) {
            $network->manager_id = null;
            $network->save();

            $partner->network_id = null;
            $partner->save();
        }

        $network
            ->partners()
            ->detach($partner);

        return back();
    }
}
