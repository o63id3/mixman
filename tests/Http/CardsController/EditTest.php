<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the edit page for admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get(route('cards.edit', Card::factory()->create()))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Cards/Edit')
            ->has('card')
        );
});

it('does not render the edit page for seller', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('cards.edit', Card::factory()->create()))
        ->assertForbidden();
});
