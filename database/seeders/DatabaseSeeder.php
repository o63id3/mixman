<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\OrderStatusEnum;
use App\Enums\RoleEnum;
use App\Models\Card;
use App\Models\Expense;
use App\Models\Network;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'النظام',
            'username' => 'system',
        ]);

        $admin = User::factory()->create([
            'name' => 'المسؤول',
            'username' => 'admin',
            'role' => RoleEnum::Ahmed,
        ]);

        if (app()->isProduction()) {
            return;
        }

        $cards = Card::factory()->createMany([[
            'name' => 'كرت فئة 1 شيكل',
            'price_for_consumer' => 1,
            'active' => true,
        ], [
            'name' => 'كرت فئة 3 شيكل',
            'price_for_consumer' => 3,
            'active' => true,
        ], [
            'name' => 'كرت فئة 5 شيكل',
            'price_for_consumer' => 5,
            'active' => true,
        ]]);

        $networks = Network::factory()
            ->afterCreating(function (Network $network) {
                $network->partners()->attach(
                    User::factory(3)->set('role', RoleEnum::Partner)->create(),
                    ['share' => 0.25]
                );
                $network->partners()->attach($network->manager, ['share' => 0.25]);
                $network->manager->update(['network_id' => $network->id]);
            })
            ->createMany([
                ['name' => 'كمال عدوان'],
                ['name' => 'مدرسة أبو حسين'],
                ['name' => 'بيت لاهيا'],
                ['name' => 'معسكر جباليا'],
            ]);

        $managers = $networks->pluck('manager');

        $sellers = User::factory(20)
            ->state(fn () => ['network_id' => $networks->random()])
            ->set('role', RoleEnum::Seller)
            ->create();

        Order::factory(5000)
            ->recycle($networks)
            ->recycle([...$sellers, ...$managers])
            ->hasAttached($cards, fn () => [
                'number_of_packages' => random_int(1, 3),
                'number_of_cards_per_package' => 120,
            ])
            ->recycle($cards)
            ->create();

        Payment::factory(5000)
            ->recycle($networks)
            ->state(fn () => ['recipient_id' => collect([...$managers, $admin])->random()])
            ->state(fn () => ['user_id' => collect([...$sellers, ...$managers])->random()])
            ->create();

        Expense::factory(1000)
            ->recycle($networks)
            ->recycle([$admin, ...$managers])
            ->create();

        Order::where('status', OrderStatusEnum::Pending)
            ->where('created_at', '<', now('Asia/Gaza')->subWeek())
            ->update([
                'status' => OrderStatusEnum::Completed,
                'completed_at' => now('Asia/Gaza')->subWeek(),
            ]);
    }
}
