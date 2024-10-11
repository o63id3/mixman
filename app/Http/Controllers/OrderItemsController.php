<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class OrderItemsController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Order $order): RedirectResponse
    {
        Gate::authorize('createCards', $order);

        $validated = $request->validate([
            'cards' => ['required', 'array'],
            'cards.*.card_id' => ['required', Rule::exists('cards', 'id')],
            'cards.*.number_of_packages' => ['required', 'numeric'],
            'cards.*.number_of_cards_per_package' => ['required', 'numeric'],
        ]);

        $order->cards()->createMany($validated['cards']);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderCard $item): RedirectResponse
    {
        Gate::authorize('createCards', $item);

        $item->delete();

        return back();
    }
}
