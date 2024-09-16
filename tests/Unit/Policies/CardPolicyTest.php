<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\User;

test('view any', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('viewAny', Card::class))->toBeTrue()
        ->and($seller->can('viewAny', Card::class))->toBeFalse();
});

test('create', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('create', Card::class))->toBeTrue()
        ->and($seller->can('create', Card::class))->toBeFalse();
});

test('update', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('update', Card::class))->toBeTrue()
        ->and($seller->can('update', Card::class))->toBeFalse();
});

test('delete', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('delete', Card::class))->toBeTrue()
        ->and($seller->can('delete', Card::class))->toBeFalse();
});
