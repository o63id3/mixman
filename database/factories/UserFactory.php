<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'region_id' => Region::factory(),
            'username' => fake()->unique()->username(),
            'password' => self::$password ??= Hash::make('password'),
            'contact_info' => 'contact info',
            'notes' => 'notes',
            'admin' => false,
            'active' => true,
            'balance' => 0,
            'remember_token' => Str::random(10),
        ];
    }
}
