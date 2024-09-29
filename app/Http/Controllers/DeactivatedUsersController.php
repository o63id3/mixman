<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class DeactivatedUsersController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(User $user): RedirectResponse
    {
        Gate::authorize('deactivate', $user);

        $user->deactivate();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('activate', $user);

        $user->activate();

        return back();
    }
}
