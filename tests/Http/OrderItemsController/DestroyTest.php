<?php

declare(strict_types=1);

use App\Models\OrderCard;
use App\Models\User;

it('allows an authorized user to delete an order item', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->delete(route('order-cards.destroy', OrderCard::factory()->create()))
        ->assertSessionHasNoErrors()
        ->assertRedirect();
});

it('prevents unauthorized users from deleting an order item', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('order-cards.destroy', OrderCard::factory()->create()))
        ->assertForbidden();
});
