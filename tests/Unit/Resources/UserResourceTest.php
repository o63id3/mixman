<?php

declare(strict_types=1);

use App\Http\Resources\RegionResource;
use App\Http\Resources\UserResource;
use App\Models\User;

test('make', function () {
    $user = User::factory()->create();

    $user->load(['region']);

    $resource = UserResource::make($user)->resolve();

    expect($resource)
        ->toHaveKey('id', $user->id)
        ->toHaveKey('region', RegionResource::make($user->region))
        ->toHaveKey('name', $user->name)
        ->toHaveKey('username', $user->username)
        ->toHaveKey('active', $user->active)
        ->toHaveKey('admin', $user->admin)
        ->toHaveKey('contact_info', $user->contact_info)
        ->toHaveKey('notes', $user->notes);
});

test('make with balance', function () {
    $user = User::factory()->create();
    $user->loadBalance();

    $resource = UserResource::make($user)->resolve();

    expect($resource)
        ->toHaveKey('balance', $user->balance);
});
