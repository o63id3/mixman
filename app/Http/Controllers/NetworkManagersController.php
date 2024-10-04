<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Network;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class NetworkManagersController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Network $network, User $user): RedirectResponse
    {
        Gate::authorize('assignManager', Network::class);

        $network->update(['manager_id' => $user->id]);
        $user->update(['network_id' => $network->id]);

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
