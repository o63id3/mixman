<?php

declare(strict_types=1);

use App\Http\Resources\CardResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\OrderResource;
use App\Models\OrderItem;

test('make', function () {
    $item = OrderItem::factory()->create();

    $item->load(['order', 'card']);

    $resource = OrderItemResource::single($item)->resolve();

    expect($resource)
        ->toHaveKey('id', $item->id)
        ->toHaveKey('order', OrderResource::single($item->order))
        ->toHaveKey('card', CardResource::single($item->card))
        ->toHaveKey('number_of_packages', $item->number_of_packages)
        ->toHaveKey('number_of_cards_per_package', $item->number_of_cards_per_package)
        ->toHaveKey('quantity', $item->quantity)
        ->toHaveKey('total_price_for_consumer', $item->total_price_for_consumer)
        ->toHaveKey('total_price_for_seller', $item->total_price_for_seller);
});
