<?php

declare(strict_types=1);

use App\Models\Region;
use App\Models\User;

test('view any', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('viewAny', Region::class))->toBeTrue()
        ->and($seller->can('viewAny', Region::class))->toBeFalse();
});

test('create', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('create', Region::class))->toBeTrue()
        ->and($seller->can('create', Region::class))->toBeFalse();
});

test('update', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('update', Region::class))->toBeTrue()
        ->and($seller->can('update', Region::class))->toBeFalse();
});

test('delete', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('delete', Region::class))->toBeTrue()
        ->and($seller->can('delete', Region::class))->toBeFalse();
});
