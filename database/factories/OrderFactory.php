<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
final class OrderFactory extends Factory
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
            'action_by' => User::factory(),
            'status' => $this->faker->randomElement(OrderStatusEnum::cases()),
            'notes' => $this->faker->realText(),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => OrderStatusEnum::Pending,
        ]);
    }

    /**
     * Indicate that the order is completed.
     */
    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => OrderStatusEnum::Completed,
        ]);
    }

    /**
     * Indicate that the order is returned.
     */
    public function returned(): static
    {
        return $this->state(fn () => [
            'status' => OrderStatusEnum::Returned,
        ]);
    }
}
