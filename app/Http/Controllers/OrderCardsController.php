<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreOrderCardsAction;
use App\Models\Order;
use App\Models\OrderCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class OrderCardsController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Order $order, StoreOrderCardsAction $action): RedirectResponse
    {
        Gate::authorize('createCards', $order);

        $validated = $request->validate([
            'cards' => ['required', 'array'],
            'cards.*.card_id' => ['required', Rule::exists('cards', 'id')],
            'cards.*.number_of_packages' => ['required', 'numeric'],
            'cards.*.number_of_cards_per_package' => ['required', 'numeric'],
        ]);

        $action->handle($order, $validated['cards']);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderCard $card): RedirectResponse
    {
        Gate::authorize('createCards', $card);

        $card->delete();

        return back();
    }
}
