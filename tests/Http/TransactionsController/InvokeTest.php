<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the index page for admin', function () {
    $user = User::factory()->admin()->create();

    Payment::factory(5)->create();
    Order::factory(5)->create();

    $this->actingAs($user)
        ->get(route('transactions.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Transactions/Index')
            ->has('transactions.data', 10)
        );
});

it('renders the index page for seller', function () {
    $user = User::factory()->user()->create();

    Payment::factory(5)->create();
    Order::factory(5)->create();
    Payment::factory(3)->create(['seller_id' => $user->id]);
    Order::factory(3)->create(['seller_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('transactions.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Transactions/Index')
            ->has('transactions.data', 6)
        );
});
