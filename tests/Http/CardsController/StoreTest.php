<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\User;

it('allows an authorized user to create a card', function () {
    $user = User::factory()->admin()->create();

    $cardData = [
        'name' => 'Test Card',
        'price_for_consumer' => 100.0,
        'price_for_seller' => 90.0,
        'notes' => 'Sample notes',
    ];

    $this->actingAs($user)
        ->post(route('cards.store'), $cardData)
        ->assertRedirect();

    $this->assertDatabaseHas('cards', $cardData);
});

it('fails validation when required fields are missing', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->post(route('cards.store'))
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
        ->post(route('cards.store'), $data)
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
        ->post(route('cards.store'), $data)
        ->assertSessionHasErrors(['name']);
});

it('prevents unauthorized users from creating a card', function () {
    $user = User::factory()->create();

    $cardData = [
        'name' => 'Test Card',
        'price_for_consumer' => 100.0,
        'price_for_seller' => 90.0,
        'notes' => 'Sample notes',
    ];

    $this->actingAs($user)
        ->post(route('cards.store'), $cardData)
        ->assertForbidden();
});
