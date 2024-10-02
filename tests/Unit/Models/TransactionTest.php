<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;

test('view', function () {
    Order::factory()->create();
    Payment::factory()->create();

    expect(Transaction::count())->toBe(2);
});

test('seller', function () {
    $seller = Seller::factory()->create();

    Order::factory()->recycle($seller)->create();
    Payment::factory()->recycle($seller)->create();

    $transactions = Transaction::with('seller')->get();

    expect($transactions->pluck('seller.id'))->each->toBe($seller->id);
});

test('scope visible to', function () {
    $admin = User::factory()->admin()->create();
    $seller = Seller::factory()->create();

    Order::factory(5)->create();
    Payment::factory(5)->create();
    expect(Transaction::visibleTo($admin)->count())->toBe(10);

    Order::factory()->recycle($seller)->create();
    Payment::factory()->recycle($seller)->create();
    expect(Transaction::visibleTo($seller)->get()->pluck('seller_id'))->each->toBe($seller->id);
});
