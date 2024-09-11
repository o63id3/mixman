<?php

declare(strict_types=1);

namespace Database\Factories;

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
        $seller = User::factory();
        $status = $this->faker->randomElement(['C', 'P', 'X']);
        $action_by = null;

        if ($status === 'X' || $status === 'C') {
            $action_by = 1;
        }

        return [
            'seller_id' => $seller,
            'action_by' => $action_by,
            'status' => $status,
            'notes' => $this->faker->realText(),
        ];
    }
}
