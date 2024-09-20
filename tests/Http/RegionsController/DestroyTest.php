<?php

declare(strict_types=1);

use App\Models\Region;
use App\Models\User;

it('allows an authorized user to delete a region', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->delete(route('regions.destroy', Region::factory()->create()))
        ->assertSessionHasNoErrors()
        ->assertRedirect();
});

it('prevents unauthorized users from updating a region', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)
        ->delete(route('regions.destroy', Region::factory()->create()))
        ->assertForbidden();
});
