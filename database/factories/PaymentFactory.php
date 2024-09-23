<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Seller;
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
        $createdAt = $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s');

        return [
            'seller_id' => Seller::factory(),
            'registered_by' => User::factory(),
            'amount' => random_int(100, 300),
            'notes' => fake()->paragraph(),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
