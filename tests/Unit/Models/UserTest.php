<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderItem;
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

test('transactions', function () {
    $user = User::factory()->user()->create();
    Order::factory(3)->create(['seller_id' => $user->id]);
    Payment::factory(3)->create(['seller_id' => $user->id]);

    expect($user->transactions->pluck('seller_id'))->each->toBe($user->id);
});

test('with balance', function () {
    $user = User::factory()->user()->create();

    // Pending do not sum
    $order = Order::factory()->create(['seller_id' => $user->id, 'status' => OrderStatusEnum::Pending]);
    $item = OrderItem::factory()->recycle($order)->create([
        'number_of_packages' => 1,
        'number_of_cards_per_package' => 120,
    ]);

    $user = User::withBalance()->find($user->id);
    expect($user->balance)->toBe(0);

    // Completed do sum
    $order = Order::factory()->create(['seller_id' => $user->id, 'status' => OrderStatusEnum::Completed]);
    $item = OrderItem::factory()->recycle($order)->create([
        'number_of_packages' => 1,
        'number_of_cards_per_package' => 120,
    ]);

    $user = User::withBalance()->find($user->id);
    expect($user->balance)->toBe((int) -$item->total_price_for_seller);

    // Returned do sum in positive
    $order = Order::factory()->create(['seller_id' => $user->id, 'status' => OrderStatusEnum::Returned]);
    $returned = OrderItem::factory()->recycle($order)->create([
        'number_of_packages' => 1,
        'number_of_cards_per_package' => 120,
    ]);

    $user = User::withBalance()->find($user->id);
    expect($user->balance)->toBe((int) (-$item->total_price_for_seller + $returned->total_price_for_seller));

    $payment = Payment::factory()->create(['seller_id' => $user->id]);

    $user = User::withBalance()->find($user->id);
    expect($user->balance)->toBe((int) (-$item->total_price_for_seller + $returned->total_price_for_seller + $payment->amount));
});

test('load balance', function () {
    $user = User::factory()->user()->create();

    $order = Order::factory()->create(['seller_id' => $user->id, 'status' => OrderStatusEnum::Completed]);
    $item = OrderItem::factory()->recycle($order)->create([
        'number_of_packages' => 1,
        'number_of_cards_per_package' => 120,
    ]);

    $user->loadBalance();
    expect($user->balance)->toBe((int) -$item->total_price_for_seller);

    $payment = Payment::factory()->create(['seller_id' => $user->id]);

    $user->loadBalance();
    expect($user->balance)->toBe((int) (-$item->total_price_for_seller + $payment->amount));
});
