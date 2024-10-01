<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Region;
use App\Models\Seller;

test('is admin', function () {
    $seller = Seller::factory()->create();

    expect($seller->isAhmed())->toBeFalse();
});

test('region', function () {
    $region = Region::factory()->create();
    $seller = Seller::factory()->recycle($region)->create();

    expect($seller->region)->toBe($region);
});

test('payments', function () {
    $seller = Seller::factory()->create();
    Payment::factory(3)->recycle($seller)->create();
    Payment::factory(3)->create();

    expect($seller->payments->pluck('seller_id'))->each->toBe($seller->id);
});

test('transactions', function () {
    $seller = Seller::factory()->create();
    Order::factory(3)->recycle($seller)->create();
    Payment::factory(3)->recycle($seller)->create();

    expect($seller->transactions->pluck('seller_id'))->each->toBe($seller->id);
});

test('with balance', function () {
    $seller = Seller::factory()->create();

    // Pending do not sum
    $order = Order::factory()->recycle($seller)->create(['status' => OrderStatusEnum::Pending]);
    $item = OrderItem::factory()->recycle($order)->create([
        'number_of_packages' => 1,
        'number_of_cards_per_package' => 120,
    ]);

    $seller = Seller::withBalance()->find($seller->id);
    expect((int) $seller->balance)->toBe(0);

    // Completed do sum
    $order = Order::factory()->recycle($seller)->create(['status' => OrderStatusEnum::Completed]);
    $item = OrderItem::factory()->recycle($order)->create([
        'number_of_packages' => 1,
        'number_of_cards_per_package' => 120,
    ]);

    $seller = Seller::withBalance()->find($seller->id);
    expect((int) $seller->balance)->toBe((int) -$item->total_price_for_seller);

    // Returned do sum in positive
    $order = Order::factory()->recycle($seller)->create(['status' => OrderStatusEnum::Returned]);
    $returned = OrderItem::factory()->recycle($order)->create([
        'number_of_packages' => 1,
        'number_of_cards_per_package' => 120,
    ]);

    $seller = Seller::withBalance()->find($seller->id);
    expect((int) $seller->balance)->toBe((int) (-$item->total_price_for_seller + $returned->total_price_for_seller));

    $payment = Payment::factory()->recycle($seller)->create();

    $seller = Seller::withBalance()->find($seller->id);
    expect((int) $seller->balance)->toBe((int) (-$item->total_price_for_seller + $returned->total_price_for_seller + $payment->amount));
});

test('load balance', function () {
    $seller = Seller::factory()->create();

    $order = Order::factory()->recycle($seller)->create(['status' => OrderStatusEnum::Completed]);
    $item = OrderItem::factory()->recycle($order)->create([
        'number_of_packages' => 1,
        'number_of_cards_per_package' => 120,
    ]);

    $seller->loadBalance();
    expect((int) $seller->balance)->toBe((int) -$item->total_price_for_seller);

    $payment = Payment::factory()->recycle($seller)->create();

    $seller->loadBalance();
    expect((int) $seller->balance)->toBe((int) (-$item->total_price_for_seller + $payment->amount));
});
