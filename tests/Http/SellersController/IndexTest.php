<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the index page for admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get(route('sellers.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Sellers/Index')
            ->has('sellers')
        );
});

it('does not render the index page for seller', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('sellers.index'))
        ->assertForbidden();
});
