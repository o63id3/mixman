<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Card;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
final class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $packages = $this->faker()->rand(1, 3);
        $price = $packages * 120;

        return [
            'order_id' => Order::factory(),
            'card_id' => Card::factory(),
            'number_of_packages' => $packages,
            'number_of_cards_per_package' => 120,
            'quantity' => $packages * 120,
            'total_price_for_consumer' => $price,
            'total_price_for_seller' => $price * 0.9,
        ];
    }
}
