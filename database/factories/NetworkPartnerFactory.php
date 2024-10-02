<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\Network;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NetworkPartner>
 */
final class NetworkPartnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(state: ['role' => RoleEnum::Partner]),
            'network_id' => Network::factory(),
            'share' => 0.25,
        ];
    }
}
