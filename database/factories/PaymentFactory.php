<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
final class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-6 month', 'now', 'Asia/Gaza')->format('Y-m-d H:i:s');

        return [
            'recipient_id' => User::factory(state: ['role' => RoleEnum::Ahmed]),
            'user_id' => User::factory(state: ['role' => RoleEnum::Partner]),
            'amount' => fake()->numberBetween(2000, 3000),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
