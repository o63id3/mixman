<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatusEnum;
use App\Http\Filters\OrderFilter;
use App\Http\Resources\OrderResource;
use App\Models\Card;
use App\Models\Network;
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
            ->with(['user', 'manager', 'network'])
            ->withSum('items as total_price_for_seller', 'total_price_for_seller')
            ->withSum('items as total_price_for_consumer', 'total_price_for_consumer')
            ->visibleTo($user)
            ->filter($filter, $user)
            ->latest()
            ->paginate(config('settings.pagination_size'));

        return Inertia::render('Orders/Index', [
            'orders' => OrderResource::collection($orders),
            'statuses' => OrderStatusEnum::cases(),
            'users' => User::visibleTo($user)->benefiter()->get(['id', 'name']),
            'managers' => User::visibleTo($user)->manager()->get(['id', 'name']),
            'networks' => Network::visibleTo($user)->get(['id', 'name']),
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
    public function create(Request $request): Response
    {
        Gate::authorize('create', Order::class);

        $user = type($request->user())->as(User::class);

        return Inertia::render('Orders/Create', [
            'users' => User::visibleTo($user)->benefiter()->get(['id', 'name']),
            'cards' => Card::all('id', 'name'),
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
            'user_id' => ['required', Rule::exists('users', 'id')],
            'status' => ['required', Rule::enum(OrderStatusEnum::class)],
            'cards' => ['required', 'array'],
            'cards.*.card_id' => ['required', Rule::exists('cards', 'id')],
            'cards.*.number_of_packages' => ['required', 'numeric'],
            'cards.*.number_of_cards_per_package' => ['required', 'numeric'],
            'notes' => ['string'],
        ]);

        $cards = $validated['cards'];
        unset($validated['cards']);

        $order = Order::create($validated);
        $order->items()->createMany($cards);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): Response
    {
        Gate::authorize('view', $order);

        $order->load(['user', 'manager', 'items', 'files', 'items.card']);
        $order->loadSum('items as total_price_for_seller', 'total_price_for_seller')
            ->loadSum('items as total_price_for_consumer', 'total_price_for_consumer');

        return Inertia::render('Orders/Show', [
            'order' => OrderResource::single($order),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Order $order): Response
    {
        Gate::authorize('update', $order);

        $user = type($request->user())->as(User::class);

        $order->load(['user', 'manager', 'items', 'files', 'items.card']);
        $order->loadSum('items as total_price_for_seller', 'total_price_for_seller')
            ->loadSum('items as total_price_for_consumer', 'total_price_for_consumer');

        return Inertia::render('Orders/Edit', [
            'order' => OrderResource::single($order),
            'users' => User::visibleTo($user)->benefiter()->get(['id', 'name']),
            'cards' => Card::get(['id', 'name']),
            'can' => [
                'update' => Gate::allows('update', $order),
                'delete' => Gate::allows('delete', $order),
                'items' => [
                    'create' => Gate::allows('createItems', $order),
                ],
                'files' => [
                    'create' => Gate::allows('createFiles', $order),
                ],
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
            'user_id' => ['required', Rule::exists('users', 'id')],
            'status' => ['required', Rule::enum(OrderStatusEnum::class)],
            'notes' => ['string'],
        ]);

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
