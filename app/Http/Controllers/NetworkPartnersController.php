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
        Gate::authorize('createPartner', $network);

        return Inertia::render('NetworkPartners/Create', [
            'network' => NetworkResource::single($network),
            'partners' => User::whereNot('role', RoleEnum::Seller)->whereNotIn('id', $network->partners->pluck('id'))->get(['id', 'name']),
            'remainingShare' => (1 - $network->partners()->sum('share')) * 100,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Network $network): RedirectResponse
    {
        Gate::authorize('createPartner', $network);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'share' => ['numeric', "max:{$network->available_share}"],
        ]);

        $network
            ->partners()
            ->attach($validated['user_id'], [
                'share' => $validated['share'] / 100,
            ]);

        return Gate::allows('createPartner', $network) ? back() : to_route('networks.edit', $network);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Network $network, User $partner): RedirectResponse
    {
        Gate::authorize('deletePartner', Network::class);

        if ($network->manager?->is($partner)) {
            $network->update(['manager_id' => null]);
            $partner->update(['network_id' => null]);
        }

        $network
            ->partners()
            ->detach($partner);

        return back();
    }
}
