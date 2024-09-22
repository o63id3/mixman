<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Models\Card;
use App\Models\User;

it('allows an authorized user to create an order', function () {
    $user = User::factory()->admin()->create();

    $cards = [
        [
            'card_id' => Card::factory()->create()->id,
            'number_of_packages' => 2,
            'number_of_cards_per_package' => 120,
        ], [
            'card_id' => Card::factory()->create()->id,
            'number_of_packages' => 2,
            'number_of_cards_per_package' => 120,
        ],
    ];

    $order = [
        'seller_id' => User::factory()->create()->id,
        'status' => OrderStatusEnum::Pending->value,
        'notes' => 'This is notes',
    ];

    $data = [
        ...$order,
        'cards' => $cards,
    ];

    $this->actingAs($user)
        ->post(route('orders.store'), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('orders', $order);
    $this->assertDatabaseHas('order_items', $cards[0]);
    $this->assertDatabaseHas('order_items', $cards[1]);
});

it('fails validation when required fields are missing', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->post(route('orders.store'))
        ->assertSessionHasErrors(['seller_id', 'status', 'cards']);
});

it('fails validation when fields are not applicable', function ($status, $number_of_packages, $number_of_cards_per_package) {
    $user = User::factory()->admin()->create();

    $data = [
        'seller_id' => 1000,
        'status' => $status,
        'cards' => [
            [
                'card_id' => 1000,
                'number_of_packages' => $number_of_packages,
                'number_of_cards_per_package' => $number_of_cards_per_package,
            ],
        ],
    ];

    $this->actingAs($user)
        ->post(route('orders.store'), $data)
        ->assertSessionHasErrors(['seller_id', 'status', 'cards.0.card_id', 'cards.0.number_of_packages', 'cards.0.number_of_cards_per_package']);
})->with([
    [1, 'asd', 'asd'],
    ['a', 'asd', 'asd'],
]);

it('prevents unauthorized users from creating an order', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('orders.store'))
        ->assertForbidden();
});
