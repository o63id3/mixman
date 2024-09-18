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
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    Order::factory(5)->create();
    expect(Order::visibleTo($admin)->count())->toBe(5);

    Order::factory()->create(['seller_id' => $seller]);
    expect(Order::visibleTo($seller)->get()->pluck('seller_id'))->each->toBe($seller->id);
});

test('seller', function () {
    $seller = User::factory()->create(['admin' => false]);
    $order = Order::factory()->create(['seller_id' => $seller]);

    expect($order->seller)->toBe($seller);
});

test('action', function () {
    $admin = User::factory()->create(['admin' => true]);
    $order = Order::factory()->create(['action_by' => $admin]);

    expect($order->action)->toBe($admin);
});

test('items', function () {
    $order = Order::factory()->hasItems(3)->create();

    expect($order->items->pluck('order_id'))->each->toBe($order->id);
});
