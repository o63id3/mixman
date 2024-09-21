<?php

declare(strict_types=1);

use App\Models\User;

it('allows an seller to create an order', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)
        ->post(route('seller-orders.store'))
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'seller_id' => $user->id,
    ]);
});

it('prevents a seller from creating more than one order within 24 hours', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)
        ->post(route('seller-orders.store'))
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $this->actingAs($user)
        ->post(route('seller-orders.store'))
        ->assertTooManyRequests();

    $this->travel(24)->hours();

    $this->actingAs($user)
        ->post(route('seller-orders.store'))
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseCount('orders', 2);
});
