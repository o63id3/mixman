<?php

declare(strict_types=1);

use App\Models\Payment;
use App\Models\Region;
use App\Models\Seller;
use App\Models\User;

test('is admin', function () {
    $user = Seller::factory()->create();
    $admin = User::factory()->admin()->create();

    expect($admin->isAdmin())->toBeTrue()->and($user->isAdmin())->toBeFalse();
});

test('region', function () {
    $region = Region::factory()->create();
    $user = User::factory()->recycle($region)->create();

    expect($user->region)->toBe($region);
});

test('payments', function () {
    $user = User::factory()->admin()->create();
    Payment::factory(3)->recycle($user)->create();
    Payment::factory(3)->create();

    expect($user->payments->pluck('registered_by'))->each->toBe($user->id);
});
