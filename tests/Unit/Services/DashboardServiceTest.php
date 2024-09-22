<?php

declare(strict_types=1);

use App\Models\Seller;
use App\Models\User;
use App\Services\DashboardService;

todo('get statistics');

todo('get max debut seller');

todo('get max region income');

test('get sellers count', function () {
    User::factory(5)->admin()->create();
    Seller::factory(5)->create();

    $service = $this->app->make(DashboardService::class);

    expect($service->getSellersCount())->toBe(5);
});

todo('get admin statistics');

todo('get total debut');

todo('get pending orders count');

todo('get returned orders count');

todo('get completed orders count');

todo('get general statistics');
