<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\User;

it('allows an authorized user to delete a card', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->delete(route('cards.destroy', Card::factory()->create()))
        ->assertSessionHasNoErrors()
        ->assertRedirect();
});

it('prevents unauthorized users from updating a card', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)
        ->delete(route('cards.destroy', Card::factory()->create()))
        ->assertForbidden();
});
