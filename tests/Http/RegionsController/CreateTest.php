<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the create page for admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get(route('regions.create'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Regions/Create')
        );
});

it('does not render the create page for seller', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('regions.create'))
        ->assertForbidden();
});
