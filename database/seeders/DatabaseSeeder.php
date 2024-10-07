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

        $partners1 = User::factory(4)->set('role', RoleEnum::Partner)->create();
        $network1 = Network::factory()->set('manager_id', $partners1->first())->set('name', 'كمال عدوان')->create();
        $partners1->first()->update(['network_id' => $network1->id]);
        $network1->partners()->attach($partners1, ['share' => 0.25]);

        $partners2 = User::factory(4)->set('role', RoleEnum::Partner)->create();
        $network2 = Network::factory()->set('manager_id', $partners2->first())->set('name', 'مدرسة أبو حسين')->create();
        $partners2->first()->update(['network_id' => $network2->id]);
        $network2->partners()->attach($partners2, ['share' => 0.25]);

        $sellers = User::factory(5)
            ->recycle([$network1, $network2])
            ->state(fn () => ['network_id' => collect([$network1, $network2])->random()])
            ->set('role', RoleEnum::Seller)
            ->create();

        Order::factory(100)
            ->recycle([$network1, $network2])
            ->recycle([...$sellers, $partners1->first(), $partners2->first()])
            ->hasItems(3)
            ->recycle($cards)
            ->create();

        Payment::factory(100)
            ->recycle([$network1, $network2])
            ->state(fn () => ['recipient_id' => collect([$partners1->first(), $partners2->first(), $admin])->random()])
            ->state(fn () => ['user_id' => collect([...$sellers, $partners1->first(), $partners2->first()])->random()])
            ->create();

        Expense::factory(100)
            ->recycle([$network1, $network2])
            ->recycle([$admin, $partners1->first(), $partners2->first()])
            ->create();

        // Order::where('status', OrderStatusEnum::Pending)
        //     ->where('created_at', '<', now()->subWeek())
        //     ->update([
        //         'status' => OrderStatusEnum::Completed,
        //     ]);
    }
}
