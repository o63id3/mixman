<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the index page for admin', function () {
    $user = User::factory()->admin()->create();

    Order::factory(5)->create();

    $this->actingAs($user)
        ->get(route('orders.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Orders/Index')
            ->has('orders.data', 5)
        );
});

it('renders the index page for seller', function () {
    $user = User::factory()->create();

    Order::factory(5)->create();
    Order::factory(3)->create(['seller_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('orders.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Orders/Index')
            ->has('orders.data', 3)
        );
});
