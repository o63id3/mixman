<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Network;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class DeactivatedNetworksController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Network $network): RedirectResponse
    {
        Gate::authorize('deactivate', Network::class);

        $network->update([
            'active' => false,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Network $network): RedirectResponse
    {
        Gate::authorize('deactivate', Network::class);

        $network->update([
            'active' => true,
        ]);

        return back();
    }
}
