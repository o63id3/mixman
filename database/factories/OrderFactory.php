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
        return [
            'seller_id' => Seller::factory(),
            'action_by' => User::factory(),
            'status' => $this->faker->randomElement(OrderStatusEnum::cases()),
            'notes' => $this->faker->realText(),
        ];
    }
}
