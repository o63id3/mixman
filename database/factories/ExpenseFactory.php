<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\Network;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
final class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s');

        return [
            'user_id' => User::factory(state: ['role' => RoleEnum::Partner]),
            'network_id' => Network::factory(),
            'description' => fake()->words(3, true),
            'amount' => fake()->numberBetween(50, 200),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
