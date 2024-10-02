<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\User;
use App\Services\DashboardService;

beforeEach(function () {
    $this->service = $this->app->make(DashboardService::class);
});

todo('get statistics');

todo('get max debut seller');

todo('get total income');

todo('get max region income');

test('get sellers count', function () {
    User::factory(5)->admin()->create();
    Seller::factory(5)->create();

    expect($this->service->getSellersCount())->toBe(5);
});

todo('get admin statistics');

todo('get total debut admin');

todo('get total debut seller');

test('get pending orders count admin', function () {
    Order::factory()->pending()->create();
    Order::factory()->completed()->create();
    Order::factory()->returned()->create();

    $admin = User::factory()->admin()->create();
    expect($this->service->getPendingOrdersCount($admin))->toBe(1);
});

test('get pending orders count seller', function () {
    Order::factory()->pending()->create();
    Order::factory()->completed()->create();
    Order::factory()->returned()->create();

    $seller = User::factory()->create();
    expect($this->service->getPendingOrdersCount($seller))->toBe(0);

    Order::factory()->pending()->create(['seller_id' => $seller->id]);
    Order::factory()->completed()->create(['seller_id' => $seller->id]);
    Order::factory()->returned()->create(['seller_id' => $seller->id]);

    expect($this->service->getPendingOrdersCount($seller))->toBe(1);
});

test('get returned orders count admin', function () {
    Order::factory()->pending()->create();
    Order::factory()->completed()->create();
    Order::factory()->returned()->create();

    $admin = User::factory()->admin()->create();
    expect($this->service->getReturnedOrdersCount($admin))->toBe(1);
});

test('get returned orders count seller', function () {
    Order::factory()->pending()->create();
    Order::factory()->completed()->create();
    Order::factory()->returned()->create();

    $seller = User::factory()->create();
    expect($this->service->getReturnedOrdersCount($seller))->toBe(0);

    Order::factory()->pending()->create(['seller_id' => $seller->id]);
    Order::factory()->completed()->create(['seller_id' => $seller->id]);
    Order::factory()->returned()->create(['seller_id' => $seller->id]);

    expect($this->service->getReturnedOrdersCount($seller))->toBe(1);
});

test('get completed orders count admin', function () {
    Order::factory()->pending()->create();
    Order::factory()->completed()->create();
    Order::factory()->returned()->create();

    $admin = User::factory()->admin()->create();
    expect($this->service->getCompletedOrdersCount($admin))->toBe(1);
});

test('get completed orders count seller', function () {
    Order::factory()->pending()->create();
    Order::factory()->completed()->create();
    Order::factory()->returned()->create();

    $seller = User::factory()->create();
    expect($this->service->getCompletedOrdersCount($seller))->toBe(0);

    Order::factory()->pending()->create(['seller_id' => $seller->id]);
    Order::factory()->completed()->create(['seller_id' => $seller->id]);
    Order::factory()->returned()->create(['seller_id' => $seller->id]);

    expect($this->service->getCompletedOrdersCount($seller))->toBe(1);
});

todo('get general statistics');
