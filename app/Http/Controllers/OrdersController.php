<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Models\Card;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class OrdersController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Order::class);

        $orders = Order::query()
            ->searchBySellerName($request->get('seller', ''))
            ->filterByStatus($request->get('status'))
            ->with(['seller', 'action'])
            ->withSum('items as total_price_for_seller', 'total_price_for_seller')
            ->withSum('items as total_price_for_consumer', 'total_price_for_consumer')
            ->latest()
            ->latest('id')
            ->seller()
            ->paginate(10);

        return Inertia::render('Orders/Index', [
            'orders' => OrderResource::collection($orders),
            'filters' => [
                'seller' => $request->get('seller'),
                'status' => $request->get('status'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Order::class);

        return Inertia::render('Orders/Create', [
            'sellers' => User::sellers()->get(),
            'cards' => Card::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Order::class);

        $validated = $request->validate([
            'seller_id' => ['required', Rule::exists('users', 'id')],
            'status' => ['required', 'in:C,P'],
            'cards' => ['array'],
            'cards.*.card_id' => ['required', Rule::exists('cards', 'id')],
            'cards.*.number_of_packages' => ['required', 'numeric'],
            'cards.*.number_of_cards_per_package' => ['required', 'numeric'],
            'notes' => ['string'],
        ]);

        $cards = $validated['cards'];
        unset($validated['cards']);

        if ($validated['status'] === 'C') {
            $validated['action_by'] = $request->user()->id;
        }

        $order = Order::create($validated);
        $order->items()->createMany($cards);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order): Response
    {
        Gate::authorize('view', $order);

        $order->load(['seller', 'action', 'items', 'items.card']);
        $order->loadSum('items as total_price_for_seller', 'total_price_for_seller')
            ->loadSum('items as total_price_for_consumer', 'total_price_for_consumer');

        OrderResource::withoutWrapping();

        return Inertia::render('Orders/Edit', [
            'sellers' => User::sellers()->get(),
            'order' => OrderResource::make($order),
            'items' => OrderItemResource::collection($order->items),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        Gate::authorize('update', $order);

        $validated = $request->validate([
            'seller_id' => ['required', Rule::exists('users', 'id')],
            'status' => ['required', 'in:C,P,X'],
            'notes' => ['string'],
        ]);

        if ($validated['status'] === 'P') {
            $validated['action_by'] = null;
        } else {
            $validated['action_by'] = $request->user()->id;
        }

        // dd($validated);

        $order->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        Gate::authorize('delete', $order);

        $order->delete();

        return to_route('orders.index');
    }
}
