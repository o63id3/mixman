<?php

declare(strict_types=1);

use App\Models\Payment;
use App\Models\Seller;
use App\Models\User;

test('seller', function () {
    $seller = Seller::factory()->create();
    $payment = Payment::factory()->recycle($seller)->create();

    expect($payment->seller)->toBe($seller);
});

test('registerer', function () {
    $registerer = User::factory()->admin()->create();
    $payment = Payment::factory()->recycle($registerer)->create();

    expect($payment->registerer)->toBe($registerer);
});

test('visible to', function () {
    $admin = User::factory()->admin()->create();
    $seller = Seller::factory()->create();

    Payment::factory(5)->create();
    expect(Payment::visibleTo($admin)->count())->toBe(5);

    Payment::factory()->recycle($seller)->create();
    expect(Payment::visibleTo($seller)->get()->pluck('seller_id'))->each->toBe($seller->id);
});
