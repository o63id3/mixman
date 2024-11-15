<?php

declare(strict_types=1);

use App\Http\Resources\PaymentResource;
use App\Http\Resources\SellerResource;
use App\Http\Resources\UserResource;
use App\Models\Payment;

test('make', function () {
    $payment = Payment::factory()->create();

    $payment->load(['seller', 'registerer']);

    $resource = PaymentResource::single($payment)->resolve();

    expect($resource)
        ->toHaveKey('id', $payment->id)
        ->toHaveKey('seller', SellerResource::single($payment->seller))
        ->toHaveKey('registerer', UserResource::single($payment->registerer))
        ->toHaveKey('amount', $payment->amount)
        ->toHaveKey('notes', $payment->notes)
        ->toHaveKey('created_at', $payment->created_at->diffForHumans());
});
