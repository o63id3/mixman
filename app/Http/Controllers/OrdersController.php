<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Http\Filters\OrderFilter;
use App\Http\Resources\CardResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
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
    public function index(Request $request, OrderFilter $filter): Response
    {
        Gate::authorize('viewAny', Order::class);

        $user = type($request->user())->as(User::class);

        $orders = Order::query()
            ->with(['orderer', 'manager'])
            ->withSum('items as total_price_for_seller', 'total_price_for_seller')
            ->withSum('items as total_price_for_consumer', 'total_price_for_consumer')
            ->visibleTo($user)
            ->filter($filter, $user)
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Orders/Index', [
            'orders' => OrderResource::collection($orders),
            'statuses' => OrderStatusEnum::cases(),
            'sellers' => User::all(),
            'filters' => $filter->filters,
            'sorts' => $filter->sorts,
            'can' => [
                'create' => Gate::allows('create', Order::class),
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
            'sellers' => User::whereNotNull('network_id')->get(['id', 'name']),
            'cards' => Card::all(),
            'statuses' => OrderStatusEnum::cases(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Order::class);

        $validated = $request->validate([
            'orderer_id' => ['required', Rule::exists('users', 'id')],
            'status' => ['required', Rule::enum(OrderStatusEnum::class)],
            'cards' => ['required', 'array'],
            'cards.*.card_id' => ['required', Rule::exists('cards', 'id')],
            'cards.*.number_of_packages' => ['required', 'numeric'],
            'cards.*.number_of_cards_per_package' => ['required', 'numeric'],
            'notes' => ['string'],
        ]);

        $cards = $validated['cards'];
        unset($validated['cards']);

        $orderer = User::find($validated['orderer_id'], ['network_id']);

        $validated['manager_id'] = $request->user()->id;
        $validated['network_id'] = $orderer->network_id;

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

        $order->load(['orderer', 'manager', 'items', 'items.card']);
        $order->loadSum('items as total_price_for_seller', 'total_price_for_seller')
            ->loadSum('items as total_price_for_consumer', 'total_price_for_consumer');

        OrderResource::withoutWrapping();
        OrderItemResource::withoutWrapping();

        return Inertia::render('Orders/Edit', [
            'users' => fn () => Gate::allows('update', $order) ? UserResource::collection(User::get()) : null,
            'order' => OrderResource::make($order),
            'items' => OrderItemResource::collection($order->items),
            'statuses' => OrderStatusEnum::cases(),
            'cards' => fn () => Gate::allows('update', $order) ? CardResource::collection(Card::get()) : null,
            'can' => [
                'update' => Gate::allows('update', $order),
                'delete' => Gate::allows('delete', $order),
                'addItem' => Gate::allows('createItems', Order::class),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        Gate::authorize('update', $order);

        $validated = $request->validate([
            'orderer_id' => ['required', Rule::exists('users', 'id')],
            'status' => ['required', Rule::enum(OrderStatusEnum::class)],
            'notes' => ['string'],
        ]);

        $orderer = User::find($validated['orderer_id'], ['network_id']);

        $validated['manager_id'] = $request->user()->id;
        $validated['network_id'] = $orderer->network_id;

        $order->update($validated);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        Gate::authorize('delete', $order);

        $order->delete();

        return to_route('orders.index');
    }
}
