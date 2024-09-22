<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the index page for admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get(route('regions.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Regions/Index')
            ->has('regions')
        );
});

it('does not render the index page for seller', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('regions.index'))
        ->assertForbidden();
});
