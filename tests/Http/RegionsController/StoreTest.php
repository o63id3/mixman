<?php

declare(strict_types=1);

use App\Models\Region;
use App\Models\User;

it('allows an authorized user to create a region', function () {
    $user = User::factory()->admin()->create();

    $data = [
        'name' => 'Test region',
    ];

    $this->actingAs($user)
        ->post(route('regions.store'), $data)
        ->assertRedirect();

    $this->assertDatabaseHas('regions', $data);
});

it('fails validation when required fields are missing', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->post(route('regions.store'))
        ->assertSessionHasErrors(['name']);
});

it('fails validation when fields are not applicable', function ($name) {
    $user = User::factory()->admin()->create();

    $data = [
        'name' => $name,
    ];

    $this->actingAs($user)
        ->post(route('regions.store'), $data)
        ->assertSessionHasErrors(['name']);
})->with([
    1,
    'a',
]);

it('fails validation when the name field is not unique', function () {
    $user = User::factory()->admin()->create();
    $region = Region::factory()->create();

    $data = [
        'name' => $region->name,
    ];

    $this->actingAs($user)
        ->post(route('regions.store'), $data)
        ->assertSessionHasErrors(['name']);
});

it('prevents unauthorized users from creating a region', function () {
    $user = User::factory()->user()->create();

    $data = [
        'name' => 'Test region',
        'price_for_consumer' => 100.0,
        'price_for_seller' => 90.0,
        'notes' => 'Sample notes',
    ];

    $this->actingAs($user)
        ->post(route('regions.store'), $data)
        ->assertForbidden();
});
