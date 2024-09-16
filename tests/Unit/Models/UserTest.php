<?php

declare(strict_types=1);

use App\Models\Payment;
use App\Models\Region;
use App\Models\User;

test('is admin', function () {
    $user = User::factory()->create();
    $admin = User::factory()->create(['admin' => true]);

    expect($admin->isAdmin())->toBeTrue()->and($user->isAdmin())->toBeFalse();
});

test('scope sellers', function () {
    User::factory()->create();
    User::factory()->create(['admin' => true]);

    expect(User::sellers()->get()->pluck('admin'))->each->toBeFalse();
});

test('region', function () {
    $region = Region::factory()->create();
    $user = User::factory()->recycle($region)->create();

    expect($user->region)->toBe($region);
});

test('payments', function () {
    $user = User::factory()->create(['admin' => true]);
    Payment::factory(3)->create(['registered_by' => $user]);
    Payment::factory(3)->create();

    expect($user->payments->pluck('registered_by'))->each->toBe($user->id);
});
