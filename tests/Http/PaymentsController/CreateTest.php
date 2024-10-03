<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the create page for admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get(route('payments.create'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Payments/Create')
            ->has('users')
        );
});

it('does not render the create page for seller', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('payments.create'))
        ->assertForbidden();
});
