<?php

declare(strict_types=1);

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

test('update', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    $order = Order::factory()->create();
    expect($admin->can('update', $order))->toBeTrue()
        ->and($seller->can('update', $order))->toBeFalse();
});

test('delete', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    $order = Order::factory()->create();
    expect($admin->can('delete', $order))->toBeTrue()
        ->and($seller->can('delete', $order))->toBeFalse();
});
