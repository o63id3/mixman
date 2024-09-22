<?php

declare(strict_types=1);

use App\Http\Resources\RegionResource;
use App\Http\Resources\SellerResource;
use App\Models\Seller;

test('make', function () {
    $seller = Seller::factory()->create();

    $seller->load(['region']);

    $resource = SellerResource::make($seller)->resolve();

    expect($resource)
        ->toHaveKey('id', $seller->id)
        ->toHaveKey('region', RegionResource::make($seller->region))
        ->toHaveKey('name', $seller->name)
        ->toHaveKey('username', $seller->username)
        ->toHaveKey('active', $seller->active)
        ->toHaveKey('contact_info', $seller->contact_info)
        ->toHaveKey('notes', $seller->notes);
});

test('make with balance', function () {
    $seller = Seller::factory()->create();
    $seller->loadBalance();

    $resource = SellerResource::make($seller)->resolve();

    expect($resource)
        ->toHaveKey('balance', $seller->balance);
});
