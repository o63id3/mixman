<?php

declare(strict_types=1);

use App\Models\User;

test('view any', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('viewAny', Seller::class))->toBeTrue()
        ->and($seller->can('viewAny', Seller::class))->toBeFalse();
});

test('create', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('create', Seller::class))->toBeTrue()
        ->and($seller->can('create', Seller::class))->toBeFalse();
});

test('update', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('update', Seller::class))->toBeTrue()
        ->and($seller->can('update', Seller::class))->toBeFalse();
});

test('delete', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('delete', Seller::class))->toBeTrue()
        ->and($seller->can('delete', Seller::class))->toBeFalse();
});
