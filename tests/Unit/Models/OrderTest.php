<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\User;

test('completed', function () {
    $completed = Order::factory()->create(['status' => OrderStatusEnum::Completed]);
    $pending = Order::factory()->create(['status' => OrderStatusEnum::Pending]);
    $returned = Order::factory()->create(['status' => OrderStatusEnum::Returned]);

    expect($completed->completed())->toBeTrue()
        ->and($pending->completed())->toBeFalse()
        ->and($returned->completed())->toBeFalse();
});

test('scope visible to', function () {
    $admin = User::factory()->admin()->create();
    $seller = Seller::factory()->create();

    Order::factory(5)->create();
    expect(Order::visibleTo($admin)->count())->toBe(5);

    Order::factory()->recycle($seller)->create();
    expect(Order::visibleTo($seller)->get()->pluck('seller_id'))->each->toBe($seller->id);
});

test('seller', function () {
    $seller = Seller::factory()->create();
    $order = Order::factory()->recycle($seller)->create();

    expect($order->seller)->toBe($seller);
});

test('action', function () {
    $admin = User::factory()->admin()->create();
    $order = Order::factory()->recycle($admin)->create(['status' => OrderStatusEnum::Completed]);

    expect($order->action)->toBe($admin);
});

test('items', function () {
    $order = Order::factory()->hasItems(3)->create();

    expect($order->items->pluck('order_id'))->each->toBe($order->id);
});
