<?php

declare(strict_types=1);

use App\Models\Payment;
use App\Models\User;

it('allows an authorized user to update a payment', function () {
    $user = User::factory()->admin()->create();

    $data = [
        'seller_id' => User::factory()->create()->id,
        'amount' => 100,
        'notes' => 'This is notes',
    ];

    $this->actingAs($user)
        ->patch(route('payments.update', Payment::factory()->create()), $data)
        ->assertSessionHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('payments', $data);
});

it('fails validation when required fields are missing', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->patch(route('payments.update', Payment::factory()->create()))
        ->assertSessionHasErrors(['seller_id', 'amount']);
});

it('fails validation when fields are not applicable', function () {
    $user = User::factory()->admin()->create();

    $data = [
        'seller_id' => 1000,
        'amount' => 'asd',
    ];

    $this->actingAs($user)
        ->patch(route('payments.update', Payment::factory()->create()), $data)
        ->assertSessionHasErrors(['seller_id', 'amount']);
});

it('prevents unauthorized users from creating a payment', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('payments.update', Payment::factory()->create()))
        ->assertForbidden();
});
