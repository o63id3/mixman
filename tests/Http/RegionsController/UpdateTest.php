<?php

declare(strict_types=1);

use App\Models\Region;
use App\Models\User;

it('allows an authorized user to update a region', function () {
    $user = User::factory()->admin()->create();

    $data = [
        'name' => 'Test Region',
    ];

    $this->actingAs($user)
        ->patch(route('regions.update', Region::factory()->create()), $data)
        ->assertRedirect();

    $this->assertDatabaseHas('regions', $data);
});

it('fails validation when required fields are missing', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->patch(route('regions.update', Region::factory()->create()))
        ->assertSessionHasErrors(['name']);
});

it('fails validation when fields are not applicable', function ($name) {
    $user = User::factory()->admin()->create();

    $data = [
        'name' => $name,
    ];

    $this->actingAs($user)
        ->patch(route('regions.update', Region::factory()->create()), $data)
        ->assertSessionHasErrors(['name']);
})->with([
    1,
    'a',
]);

it('fails validation when the name field is not unique', function () {
    $user = User::factory()->admin()->create();
    $Region = Region::factory()->create();

    $data = [
        'name' => $Region->name,
    ];

    $this->actingAs($user)
        ->patch(route('regions.update', Region::factory()->create()), $data)
        ->assertSessionHasErrors(['name']);
});

it('prevents unauthorized users from updating a region', function () {
    $user = User::factory()->user()->create();

    $data = [
        'name' => 'Test Region',
    ];

    $this->actingAs($user)
        ->patch(route('regions.update', Region::factory()->create()), $data)
        ->assertForbidden();
});
