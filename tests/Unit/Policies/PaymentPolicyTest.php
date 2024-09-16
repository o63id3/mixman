<?php

declare(strict_types=1);

use App\Models\Payment;
use App\Models\User;

test('view any', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('viewAny', Payment::class))->toBeTrue()
        ->and($seller->can('viewAny', Payment::class))->toBeTrue();
});

test('create', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('create', Payment::class))->toBeTrue()
        ->and($seller->can('create', Payment::class))->toBeFalse();
});

test('update', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('update', Payment::class))->toBeTrue()
        ->and($seller->can('update', Payment::class))->toBeFalse();
});

test('delete', function () {
    $admin = User::factory()->create(['admin' => true]);
    $seller = User::factory()->create(['admin' => false]);

    expect($admin->can('delete', Payment::class))->toBeTrue()
        ->and($seller->can('delete', Payment::class))->toBeFalse();
});
