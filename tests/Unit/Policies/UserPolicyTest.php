<?php

declare(strict_types=1);

use App\Models\User;

test('view any', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('viewAny', User::class))->toBeTrue()
        ->and($seller->can('viewAny', User::class))->toBeFalse();
});

test('create', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('create', User::class))->toBeTrue()
        ->and($seller->can('create', User::class))->toBeFalse();
});

test('update', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('update', User::class))->toBeTrue()
        ->and($seller->can('update', User::class))->toBeFalse();
});

test('delete', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('delete', User::class))->toBeTrue()
        ->and($seller->can('delete', User::class))->toBeFalse();
});
