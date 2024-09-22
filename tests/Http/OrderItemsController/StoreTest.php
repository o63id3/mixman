<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\Order;
use App\Models\User;

it('allows an authorized user to create an order item', function () {
    $user = User::factory()->admin()->create();

    $data = [
        'cards' => [
            [
                'card_id' => Card::factory()->create()->id,
                'number_of_packages' => 2,
                'number_of_cards_per_package' => 120,
            ], [
                'card_id' => Card::factory()->create()->id,
                'number_of_packages' => 2,
                'number_of_cards_per_package' => 120,
            ],
        ]];

    $this->actingAs($user)
        ->post(route('order-items.store', Order::factory()->create()), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('order_items', $data['cards'][0]);
    $this->assertDatabaseHas('order_items', $data['cards'][1]);
});

it('fails validation when required fields are missing', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->post(route('order-items.store', Order::factory()->create()))
        ->assertSessionHasErrors(['cards']);
});

it('fails validation when fields are not applicable', function ($number_of_packages, $number_of_cards_per_package) {
    $user = User::factory()->admin()->create();

    $data = [
        'cards' => [
            [
                'card_id' => 1000,
                'number_of_packages' => $number_of_packages,
                'number_of_cards_per_package' => $number_of_cards_per_package,
            ],
        ],
    ];

    $this->actingAs($user)
        ->post(route('order-items.store', Order::factory()->create()), $data)
        ->assertSessionHasErrors(['cards.0.card_id', 'cards.0.number_of_packages', 'cards.0.number_of_cards_per_package']);
})->with([
    ['asd', 'asd'],
    ['asd', 'asd'],
]);

it('prevents unauthorized users from creating an order item', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('order-items.store', Order::factory()->create()))
        ->assertForbidden();
});
