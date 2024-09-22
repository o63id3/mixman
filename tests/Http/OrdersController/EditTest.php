<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the edit page for admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get(route('orders.edit', Order::factory()->create()))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Orders/Edit')
            ->has('order')
            ->has('sellers')
        );
});

it('does not render the edit page for seller', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('orders.edit', Order::factory()->create()))
        ->assertForbidden();
});
