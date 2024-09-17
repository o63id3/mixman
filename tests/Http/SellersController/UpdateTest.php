<?php

declare(strict_types=1);

use App\Models\Region;
use App\Models\User;

it('allows an authorized user to update a seller', function () {
    $user = User::factory()->admin()->create();

    $data = [
        'name' => 'Test user',
        'region' => Region::factory()->create()->id,
        'username' => 'test',
        'password' => '1234',
        'contact_info' => 'Sample contact info',
        'notes' => 'Sample notes',
    ];

    $this->actingAs($user)
        ->patch(route('sellers.update', User::factory()->create()), $data)
        ->assertRedirect();

    $this->assertDatabaseHas('users', [
        'name' => $data['name'],
        'region_id' => $data['region'],
        'username' => $data['username'],
        'contact_info' => $data['contact_info'],
        'notes' => $data['notes'],
    ]);
});

it('fails validation when required fields are missing', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->patch(route('sellers.update', User::factory()->create()))
        ->assertSessionHasErrors(['name']);
});

it('fails validation when fields are not applicable', function ($name, $region, $username, $password) {
    $user = User::factory()->admin()->create();

    $data = [
        'name' => $name,
        'region' => $region,
        'username' => $username,
        'password' => $password,
    ];

    $this->actingAs($user)
        ->patch(route('sellers.update', User::factory()->create()), $data)
        ->assertSessionHasErrors(['name', 'region', 'username', 'password']);
})->with([
    [1, 1000, 123, 1233],
    ['a', 1000, 'a', 'asd'],
]);

it('fails validation when the name field is not unique', function () {
    $user = User::factory()->admin()->create();

    $data = [
        'name' => 'Test seller',
        'region' => Region::factory()->create()->id,
        'username' => User::factory()->create()->username,
        'password' => '1234',
    ];

    $this->actingAs($user)
        ->patch(route('sellers.update', User::factory()->create()), $data)
        ->assertSessionHasErrors(['username']);
});

it('prevents unauthorized users from updating a seller', function () {
    $user = User::factory()->user()->create();

    $this->actingAs($user)
        ->patch(route('sellers.update', User::factory()->create()))
        ->assertForbidden();
});
