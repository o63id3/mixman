<?php

declare(strict_types=1);

use App\Models\Payment;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the index page for admin', function () {
    $user = User::factory()->admin()->create();

    Payment::factory(5)->create();

    $this->actingAs($user)
        ->get(route('payments.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Payments/Index')
            ->has('payments.data', 5)
        );
});

it('renders the index page for seller', function () {
    $user = User::factory()->user()->create();

    Payment::factory(5)->create();
    Payment::factory(3)->create(['seller_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('payments.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Payments/Index')
            ->has('payments.data', 3)
        );
});
