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
        $admin = User::factory()->create([
            'name' => 'المسؤول',
            'username' => 'admin',
            'role' => RoleEnum::Ahmed,
        ]);

        if (app()->isProduction()) {
            return;
        }

        Card::factory()->createMany([[
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

        $partners = User::factory(4)->set('role', RoleEnum::Partner)->create();
        $network1 = Network::factory()->set('manager_id', $partners->random())->set('name', 'كمال عدوان')->create();
        $network1->partners()->attach($partners, ['share' => 0.25]);

        $partners = User::factory(4)->set('role', RoleEnum::Partner)->create();
        $network2 = Network::factory()->set('manager_id', $partners->random())->set('name', 'مدرسة أبو حسين')->create();
        $network2->partners()->attach($partners, ['share' => 0.25]);

        Order::factory(5)->recycle([$network1, $network2])->create();
        Payment::factory(5)->recycle([$network1, $network2])->create();
        Expense::factory(5)->recycle([$network1, $network2])->create();

        // Order::factory(100)
        //     ->recycle($sellers)
        //     ->recycle($admin)
        //     ->hasItems(3)
        //     ->recycle($cards)
        //     ->pending()
        //     ->create();

        // Order::factory(100)
        //     ->recycle($sellers)
        //     ->recycle($admin)
        //     ->hasItems(3)
        //     ->recycle($cards)
        //     ->completed()
        //     ->create();

        // Order::factory(20)
        //     ->recycle($sellers)
        //     ->recycle($admin)
        //     ->hasItems(1)
        //     ->recycle($cards)
        //     ->returned()
        //     ->create();

        // Payment::factory(100)
        //     ->recycle($sellers)
        //     ->recycle($admin)
        //     ->create();

        // Order::where('status', OrderStatusEnum::Pending)
        //     ->where('created_at', '<', now()->subWeek())
        //     ->update([
        //         'status' => OrderStatusEnum::Completed,
        //         'action_by' => $admin->id,
        //     ]);
    }
}
