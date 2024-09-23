<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Region;
use App\Models\Seller;
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
            'region_id' => null,
            'name' => 'المسؤول',
            'username' => 'admin',
            'password' => '1',
            'admin' => true,
        ]);

        $regions = Region::factory()->createMany([[
            'name' => 'كمال عدوان',
        ], [
            'name' => 'الفالوجا',
        ], [
            'name' => 'المشروع',
        ]]);

        $sellers = Seller::factory(15)->recycle($regions)->create();

        Card::factory()->createMany([[
            'name' => 'كرت فئة 1 شيكل',
            'price_for_consumer' => 1,
            'price_for_seller' => 0.9,
            'active' => true,
        ], [
            'name' => 'كرت فئة 3 شيكل',
            'price_for_consumer' => 3,
            'price_for_seller' => 3 * 0.9,
            'active' => true,
        ], [
            'name' => 'كرت فئة 5 شيكل',
            'price_for_consumer' => 5,
            'price_for_seller' => 5 * 0.9,
            'active' => true,
        ]]);

        Order::factory(200)
            ->hasItems(3)
            ->recycle($sellers)
            ->recycle($admin)
            ->create();

        Payment::factory(100)
            ->recycle($sellers)
            ->recycle($admin)
            ->create();
    }
}
