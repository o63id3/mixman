<?php

declare(strict_types=1);

use App\Models\OrderItem;
use App\Models\User;

test('view any', function () {
    $admin = User::factory()->admin()->create();
    $seller = User::factory()->user()->create();

    expect($admin->can('viewAny', OrderItem::class))->toBeTrue()
        ->and($seller->can('viewAny', OrderItem::class))->toBeFalse();
});

test('delete', function () {
    $admin = User::factory()->admin()->create();
    $seller = User::factory()->user()->create();

    $item = OrderItem::factory()->create();
    expect($admin->can('delete', $item))->toBeTrue()
        ->and($seller->can('delete', $item))->toBeFalse();
});
