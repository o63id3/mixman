<?php

declare(strict_types=1);

use App\Models\Region;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the edit page for admin', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get(route('regions.edit', Region::factory()->create()))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Regions/Edit')
            ->has('region')
        );
});

it('does not render the edit page for seller', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('regions.edit', Region::factory()->create()))
        ->assertForbidden();
});
