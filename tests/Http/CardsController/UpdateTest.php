<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\User;

it('allows an authorized user to update a card', function () {
    $user = User::factory()->admin()->create();

    $cardData = [
        'name' => 'Test Card',
        'price_for_consumer' => 100.0,
        'price_for_seller' => 90.0,
        'notes' => 'Sample notes',
    ];

    $this->actingAs($user)
        ->patch(route('cards.update', Card::factory()->create()), $cardData)
        ->assertRedirect();

    $this->assertDatabaseHas('cards', $cardData);
});

it('fails validation when required fields are missing', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->patch(route('cards.update', Card::factory()->create()))
        ->assertSessionHasErrors(['name', 'price_for_consumer', 'price_for_seller']);
});

it('fails validation when fields are not applicable', function ($name, $price_for_consumer, $price_for_seller) {
    $user = User::factory()->admin()->create();

    $data = [
        'name' => $name,
        'price_for_consumer' => $price_for_consumer,
        'price_for_seller' => $price_for_seller,
    ];

    $this->actingAs($user)
        ->patch(route('cards.update', Card::factory()->create()), $data)
        ->assertSessionHasErrors(['name', 'price_for_consumer', 'price_for_seller']);
})->with([
    [1, 'asd', 'asd'],
    ['a', 'asd', 'asd'],
]);

it('fails validation when the name field is not unique', function () {
    $user = User::factory()->admin()->create();
    $card = Card::factory()->create();

    $data = [
        'name' => $card->name,
        'price_for_consumer' => 1,
        'price_for_seller' => 0.9,
    ];

    $this->actingAs($user)
        ->patch(route('cards.update', Card::factory()->create()), $data)
        ->assertSessionHasErrors(['name']);
});

it('prevents unauthorized users from updating a card', function () {
    $user = User::factory()->create();

    $cardData = [
        'name' => 'Test Card',
        'price_for_consumer' => 100.0,
        'price_for_seller' => 90.0,
        'notes' => 'Sample notes',
    ];

    $this->actingAs($user)
        ->patch(route('cards.update', Card::factory()->create()), $cardData)
        ->assertForbidden();
});
