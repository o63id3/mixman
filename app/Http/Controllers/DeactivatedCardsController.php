<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

final class DeactivatedCardsController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Card $card): RedirectResponse
    {
        Gate::authorize('deactivate', Card::class);

        $card->update([
            'active' => false,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card): RedirectResponse
    {
        Gate::authorize('activate', Card::class);

        $card->update([
            'active' => true,
        ]);

        return back();
    }
}
