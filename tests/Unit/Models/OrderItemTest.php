<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\Order;
use App\Models\OrderItem;

test('order', function () {
    $order = Order::factory()->create();
    $item = OrderItem::factory()->recycle($order)->create();

    expect($item->order)->toBe($order);
});

test('card', function () {
    $card = Card::factory()->create();
    $item = OrderItem::factory()->recycle($card)->create();

    expect($item->card)->toBe($card);
});
