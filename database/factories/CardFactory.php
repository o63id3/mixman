<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
final class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = random_int(1, 10);

        return [
            'name' => $this->faker->words(3, true),
            'price_for_consumer' => $price,
            'active' => $this->faker->randomElement([true, false]),
            'notes' => $this->faker->realText(),
        ];
    }
}
