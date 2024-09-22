<?php

declare(strict_types=1);

use App\Http\Resources\OrderResource;
use App\Http\Resources\SellerResource;
use App\Http\Resources\UserResource;
use App\Models\Order;

test('make', function () {
    $order = Order::factory()->hasItems(1)->create();

    $order->load(['seller', 'action']);
    $order->loadSum('items as total_price_for_seller', 'total_price_for_seller')
        ->loadSum('items as total_price_for_consumer', 'total_price_for_consumer');

    $resource = OrderResource::make($order)->resolve();

    expect($resource)
        ->toHaveKey('id', $order->id)
        ->toHaveKey('status', $order->status)
        ->toHaveKey('seller', SellerResource::make($order->seller))
        ->toHaveKey('action', $order->action ? UserResource::make($order->action) : null)
        ->toHaveKey('total_price_for_seller', $order->total_price_for_seller)
        ->toHaveKey('total_price_for_consumer', $order->total_price_for_consumer)
        ->toHaveKey('updated_at', $order->updated_at->diffForHumans())
        ->toHaveKey('can', [
            'view' => auth()->user()?->can('view', Order::class),
            'update' => auth()->user()?->can('update', Order::class),
            'delete' => auth()->user()?->can('delete', Order::class),
        ]);
});
