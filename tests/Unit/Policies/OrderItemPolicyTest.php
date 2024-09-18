<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

test('view any', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('viewAny', OrderItem::class))->toBeTrue()
        ->and($seller->can('viewAny', OrderItem::class))->toBeFalse();
});

test('delete', function ($status) {
    $admin = User::factory()->create(['admin' => true]);

    $order = Order::factory()->create(['status' => OrderStatusEnum::Completed]);
    $item = OrderItem::factory()->recycle($order)->create();
    expect($admin->can('delete', $item))->toBeFalse();

    $order = Order::factory()->create(['status' => $status]);
    $item = OrderItem::factory()->recycle($order)->create();
    expect($admin->can('delete', $item))->toBeTrue();

    $seller = User::factory()->create(['admin' => false]);
    $item = OrderItem::factory()->recycle($order)->create();
    expect($seller->can('delete', $item))->toBeFalse();
})->with([
    OrderStatusEnum::Pending,
    OrderStatusEnum::Returned,
]);
