<?php

declare(strict_types=1);

use App\Models\Payment;
use App\Models\User;

test('seller', function () {
    $seller = User::factory()->create(['admin' => false]);
    $payment = Payment::factory()->create(['seller_id' => $seller]);

    expect($payment->seller)->toBe($seller);
});

test('registerer', function () {
    $registerer = User::factory()->create(['admin' => true]);
    $payment = Payment::factory()->create(['registered_by' => $registerer]);

    expect($payment->registerer)->toBe($registerer);
});
