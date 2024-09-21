<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\User;

test('view any', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('viewAny', Order::class))->toBeTrue()
        ->and($seller->can('viewAny', Order::class))->toBeTrue();
});

test('view', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    $order = Order::factory()->create();
    expect($admin->can('view', $order))->toBeTrue()
        ->and($seller->can('view', $order))->toBeFalse();

    $order = Order::factory()->create(['seller_id' => $seller]);
    expect($seller->can('view', $order))->toBeTrue();
});

test('create', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('create', Order::class))->toBeTrue()
        ->and($seller->can('create', Order::class))->toBeFalse();
});

test('create items', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('createItems', Order::class))->toBeTrue()
        ->and($seller->can('createItems', Order::class))->toBeFalse();
});

test('update', function ($status) {
    $admin = User::factory()->create(['admin' => true]);

    $order = Order::factory()->create(['status' => OrderStatusEnum::Completed]);
    expect($admin->can('update', $order))->toBeTrue();

    $order = Order::factory()->create(['status' => $status]);
    expect($admin->can('update', $order))->toBeTrue();

    $seller = User::factory()->create(['admin' => false]);

    $order = Order::factory()->create();
    expect($seller->can('update', $order))->toBeFalse();

    $order = Order::factory()->create(['status' => OrderStatusEnum::Completed, 'seller_id' => $seller]);
    expect($seller->can('update', $order))->toBeFalse();

    $order = Order::factory()->create(['status' => $status, 'seller_id' => $seller]);
    expect($seller->can('update', $order))->toBeTrue();
})->with([
    OrderStatusEnum::Pending,
    OrderStatusEnum::Returned,
]);

test('delete', function ($status) {
    $admin = User::factory()->create(['admin' => true]);

    $order = Order::factory()->create(['status' => OrderStatusEnum::Completed]);
    expect($admin->can('delete', $order))->toBeTrue();

    $order = Order::factory()->create(['status' => $status]);
    expect($admin->can('delete', $order))->toBeTrue();

    $seller = User::factory()->create(['admin' => false]);

    $order = Order::factory()->create();
    expect($seller->can('delete', $order))->toBeFalse();

    $order = Order::factory()->create(['status' => OrderStatusEnum::Completed, 'seller_id' => $seller]);
    expect($seller->can('delete', $order))->toBeFalse();

    $order = Order::factory()->create(['status' => $status, 'seller_id' => $seller]);
    expect($seller->can('delete', $order))->toBeTrue();
})->with([
    OrderStatusEnum::Pending,
    OrderStatusEnum::Returned,
]);
