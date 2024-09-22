<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\User;

it('allows an authorized user to update an order', function () {
    $user = User::factory()->admin()->create();

    $order = [
        'seller_id' => User::factory()->create()->id,
        'status' => OrderStatusEnum::Pending->value,
        'notes' => 'This is notes',
    ];

    $data = [
        ...$order,
    ];

    $this->actingAs($user)
        ->patch(route('orders.update', Order::factory()->create()), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('orders', $order);
});

it('fails validation when required fields are missing', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->patch(route('orders.update', Order::factory()->create()))
        ->assertSessionHasErrors(['seller_id', 'status']);
});

it('fails validation when fields are not applicable', function ($status) {
    $user = User::factory()->admin()->create();

    $data = [
        'seller_id' => 1000,
        'status' => $status,
    ];

    $this->actingAs($user)
        ->patch(route('orders.update', Order::factory()->create()), $data)
        ->assertSessionHasErrors(['seller_id', 'status']);
})->with([
    1,
    'a',
]);

it('prevents unauthorized users from creating an order', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('orders.update', Order::factory()->create()))
        ->assertForbidden();
});
