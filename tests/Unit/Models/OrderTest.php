<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\User;

test('completed', function () {
    $completed = Order::factory()->create(['status' => 'C']);
    $canceled = Order::factory()->create(['status' => 'X']);
    $processing = Order::factory()->create(['status' => 'X']);

    expect($completed->completed())->toBeTrue()
        ->and($canceled->completed())->toBeFalse()
        ->and($processing->completed())->toBeFalse();
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
